<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobSeekerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class UserController extends Controller
{
    // ──────────────────────────────────────────────────────
    // LIST ALL USERS — Admin only
    // ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) abort(403);

        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name',  'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('user_type', $request->type);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('users.index', compact('users'));
    }

    // ──────────────────────────────────────────────────────
    // SHOW SINGLE USER — Admin only
    // ──────────────────────────────────────────────────────
    public function show($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) abort(403);
        $user = User::with(['jobs', 'skills'])->findOrFail($id);
        return view('users.show', compact('user'));
    }

    // ──────────────────────────────────────────────────────
    // REGISTER
    // ──────────────────────────────────────────────────────
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'regex:/^\p{L}+(?:\s\p{L}+)*$/u'],

            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
            ],

            'phone'    => ['nullable', 'regex:/^\+?\d{8,20}$/'],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^[\x20-\x7E]+$/',
            ],

            'user_type'       => 'required|in:JobOwner,JobSeeker',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'terms'           => 'accepted',
        ], [
            'email.regex'    => 'Email address must contain English characters only.',
            'password.regex' => 'Password must contain English characters only.',
            'name.regex'     => 'Name must contain only letters and spaces, and must start with a letter.',
        ]);

        unset($data['terms']);

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user = DB::transaction(function () use ($data) {
            $user = User::create($data);

            if ($user->user_type === 'JobSeeker') {
                $user->jobSeekerProfile()->create(['city' => null]);
            }

            return $user;
        });

        Auth::login($user);

        return $this->redirectToDashboard($user);
    }

    // ──────────────────────────────────────────────────────
    // LOGIN
    // ──────────────────────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
            ],

            'password' => [
                'required',
                'string',
                'regex:/^[\x20-\x7E]+$/',
            ],
        ], [
            'email.regex'    => 'Email address must contain English characters only.',
            'password.regex' => 'Password must contain English characters only.',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->redirectToDashboard(Auth::user());
        }

        return back()->withErrors([
            'email' => 'Wrong email or password',
        ])->withInput($request->only('email', 'remember'));
    }

    // ──────────────────────────────────────────────────────
    // LOGOUT
    // ──────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ──────────────────────────────────────────────────────
    // UPDATE OWN PROFILE — Self only, no admin bypass
    // ──────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Strictly self-only — admins use adminUpdate() instead
        if (Auth::id() !== $user->id) {
            abort(403, 'You can only edit your own profile.');
        }

        $data = $request->validate([
            'name'            => ['sometimes', 'string', 'max:100', 'regex:/^\p{L}+(?:\s\p{L}+)*$/u'],
            'phone'           => ['nullable', 'regex:/^\+?\d{8,20}$/'],
            'password'        => ['nullable', 'string', 'min:8', 'confirmed', 'regex:/^[\x20-\x7E]+$/'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'password.regex' => 'Password must contain English characters only.',
            'name.regex'     => 'Name must contain only letters and spaces, and must start with a letter.',
        ]);

        // Users can never change their own user_type
        unset($data['user_type']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // ──────────────────────────────────────────────────────
    // DELETE USER — Admin only
    // ──────────────────────────────────────────────────────
    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($id);

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    // ──────────────────────────────────────────────────────
    // SEND PASSWORD RESET LINK
    // ──────────────────────────────────────────────────────
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
            ],
        ], [
            'email.regex' => 'Email address must contain English characters only.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // ──────────────────────────────────────────────────────
    // RESET PASSWORD
    // ──────────────────────────────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',

            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^[\x20-\x7E]+$/',
            ],
        ], [
            'email.regex'    => 'Email address must contain English characters only.',
            'password.regex' => 'Password must contain English characters only.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password reset successfully.')
            : back()->withErrors(['email' => [__($status)]]);
    }

    // ──────────────────────────────────────────────────────
    // HELPER — Redirect to correct dashboard by role
    // ──────────────────────────────────────────────────────
    protected function redirectToDashboard($user)
    {
        return match ($user->user_type) {
            'Admin'     => redirect()->route('dashboard.admin'),
            'JobOwner'  => redirect()->route('dashboard.jobowner'),
            'JobSeeker' => redirect()->route('dashboard.jobseeker'),
            default     => redirect()->route('login'),
        };
    }
}