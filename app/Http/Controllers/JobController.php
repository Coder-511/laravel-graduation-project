<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JobController extends Controller
{
    // ──────────────────────────────────────────────────────
    // LIST JOBS
    // Admin sees all jobs; JobOwner sees only their own
    // ──────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Job::with(['owner', 'shifts', 'skills'])
            ->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Rejected')")
            ->orderBy('created_at', 'desc');

        if ($user->isJobOwner()) {
            $query->where('owner_id', $user->id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($user->isAdmin() && $request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->get();

        return view('jobs.index', compact('jobs'));
    }

    // ──────────────────────────────────────────────────────
    // SHOW SINGLE JOB
    // ──────────────────────────────────────────────────────
    public function show($id)
    {
        $job  = Job::with(['owner', 'approvedBy', 'shifts', 'skills'])->findOrFail($id);
        $user = Auth::user();

        if (!$user->isAdmin() && $job->owner_id !== $user->id) {
            abort(403);
        }

        return view('jobs.show', compact('job'));
    }

    // ──────────────────────────────────────────────────────
    // STORE NEW JOB — Admin + JobOwner
    // ──────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'location'    => ['nullable', 'string', 'max:150'],
            'salary'      => ['nullable', 'numeric', 'min:0'],
            'skill_ids'   => ['nullable', 'array'],
            'skill_ids.*' => ['integer', 'exists:skills,skill_id'],
        ]);

        $data['owner_id'] = $user->id;

        if ($user->isAdmin()) {
            $data['status']      = 'Approved';
            $data['approved_by'] = $user->id;
            $data['approved_at'] = Carbon::now();
        } else {
            $data['status'] = 'Pending';
        }

        $job = Job::create($data);

        if (!empty($data['skill_ids'])) {
            $job->skills()->sync($data['skill_ids']);
        }

        // ── Notify all admins about the new pending job ──
        if (!$user->isAdmin()) {
            $admins = User::where('user_type', 'Admin')->get();
            foreach ($admins as $admin) {
                Notification::notify(
                    $admin->id,
                    'New Job Pending Approval',
                    "A new job \"{$job->title}\" has been submitted by {$user->name} and is waiting for your review."
                );
            }
        }

        $message = $user->isAdmin()
            ? 'Job posted and approved successfully.'
            : 'Job posted successfully. Awaiting admin approval.';

        return redirect()->route('jobs.index')->with('success', $message);
    }

    // ──────────────────────────────────────────────────────
    // UPDATE JOB
    // Admin can edit any job; JobOwner can only edit their own
    // ──────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $job  = Job::findOrFail($id);
        $user = Auth::user();

        if (!$user->isAdmin() && $job->owner_id !== $user->id) {
            abort(403, 'You can only edit jobs you posted.');
        }

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'location'    => ['nullable', 'string', 'max:150'],
            'salary'      => ['nullable', 'numeric', 'min:0'],
            'skill_ids'   => ['nullable', 'array'],
            'skill_ids.*' => ['integer', 'exists:skills,skill_id'],

            'status' => ['sometimes', 'in:Pending,Approved,Rejected', function ($attr, $value, $fail) use ($user) {
                if (!$user->isAdmin()) {
                    $fail('Only admins can change job status.');
                }
            }],
        ]);

        if (!$user->isAdmin() && in_array($job->status, ['Approved', 'Rejected'])) {
            $data['status']           = 'Pending';
            $data['approved_by']      = null;
            $data['approved_at']      = null;
            $data['rejection_reason'] = null;
        }

        if ($user->isAdmin() && isset($data['status']) && $data['status'] === 'Approved') {
            $data['approved_by'] = $user->id;
            $data['approved_at'] = Carbon::now();
        }

        $job->update($data);

        if ($request->has('skill_ids')) {
            $job->skills()->sync($data['skill_ids'] ?? []);
        }

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    // ──────────────────────────────────────────────────────
    // DELETE JOB
    // ──────────────────────────────────────────────────────
    public function destroy($id)
    {
        $job  = Job::findOrFail($id);
        $user = Auth::user();

        if (!$user->isAdmin() && $job->owner_id !== $user->id) {
            abort(403);
        }

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }

    // ──────────────────────────────────────────────────────
    // APPROVE JOB — Admin only
    // ──────────────────────────────────────────────────────
    public function approve($id)
    {
        $job  = Job::findOrFail($id);
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if ($job->status !== 'Pending') {
            return redirect()->back()->withErrors(['error' => 'Only pending jobs can be approved.']);
        }

        $job->update([
            'status'           => 'Approved',
            'approved_by'      => $user->id,
            'approved_at'      => Carbon::now(),
            'rejection_reason' => null,
        ]);

        // ── Notify the job owner ──
        Notification::notify(
            $job->owner_id,
            'Your Job Has Been Approved',
            "Great news! Your job \"{$job->title}\" has been approved and is now live."
        );

        return redirect()->back()->with('success', 'Job approved successfully.');
    }

    // ──────────────────────────────────────────────────────
    // REJECT JOB — Admin only, reason required
    // ──────────────────────────────────────────────────────
    public function reject(Request $request, $id)
    {
        $job  = Job::findOrFail($id);
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if ($job->status !== 'Pending') {
            return redirect()->back()->withErrors(['error' => 'Only pending jobs can be rejected.']);
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:255'],
        ]);

        $job->update([
            'status'           => 'Rejected',
            'approved_by'      => $user->id,
            'approved_at'      => Carbon::now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // ── Notify the job owner ──
        Notification::notify(
            $job->owner_id,
            'Your Job Was Rejected',
            "Your job \"{$job->title}\" was rejected. Reason: {$request->rejection_reason}"
        );

        return redirect()->back()->with('success', 'Job rejected.');
    }
}