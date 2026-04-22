<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobSeekerController extends Controller
{
    // ── Check if the user still needs onboarding ──────────
    private function needsOnboarding(): bool
    {
        $user = Auth::user();
        return !$user->jobSeekerProfile?->city
            || $user->skills->isEmpty();
    }

    // ── Dashboard ─────────────────────────────────────────
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->isJobSeeker()) abort(403);

        if ($this->needsOnboarding()) {
            return redirect()->route('jobseeker.onboarding');
        }

        $recentJobs = Job::with(['skills', 'owner', 'shifts'])
            ->where('status', 'Approved')
            ->latest()
            ->take(6)
            ->get();

        $appliedJobIds = JobApplication::where('seeker_id', $user->id)
            ->pluck('job_id')
            ->toArray();

        $totalApplications = JobApplication::where('seeker_id', $user->id)->count();
        $pendingApplications = JobApplication::where('seeker_id', $user->id)
            ->where('status', 'Pending')->count();
        $acceptedApplications = JobApplication::where('seeker_id', $user->id)
            ->where('status', 'Accepted')->count();

        return view('dashboard.jobseeker.dashboard', compact(
            'recentJobs',
            'appliedJobIds',
            'totalApplications',
            'pendingApplications',
            'acceptedApplications'
        ));
    }

    // ── Onboarding ────────────────────────────────────────
    public function onboarding()
    {
        $user = Auth::user();
        if (!$user->isJobSeeker()) abort(403);

        if (!$this->needsOnboarding()) {
            return redirect()->route('dashboard.jobseeker');
        }

        $skills           = Skill::orderBy('skill_name')->get();
        $selectedSkillIds = $user->skills->pluck('skill_id')->toArray();

        return view('dashboard.jobseeker.onboarding',
            compact('skills', 'selectedSkillIds'));
    }

    public function completeOnboarding(Request $request)
    {
        $user = Auth::user();
        if (!$user->isJobSeeker()) abort(403);

        $request->validate([
            'city'        => ['required', 'string', 'max:100'],
            'skill_ids'   => ['required', 'array', 'min:1'],
            'skill_ids.*' => ['integer', 'exists:skills,skill_id'],
        ], [
            'skill_ids.required' => 'Please select at least one skill.',
            'skill_ids.min'      => 'Please select at least one skill.',
        ]);

        $user->jobSeekerProfile()->updateOrCreate(
            ['seeker_id' => $user->id],
            ['city'      => $request->city]
        );

        $user->skills()->sync($request->skill_ids);

        return redirect()->route('dashboard.jobseeker')
            ->with('success', 'Welcome! Your profile is all set up. 🎉');
    }

    // ── Browse Jobs ───────────────────────────────────────
    public function jobs(Request $request)
    {
        $user = Auth::user();
        if (!$user->isJobSeeker()) abort(403);

        $query = Job::with(['skills', 'owner', 'shifts'])
            ->where('status', 'Approved');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title',    'like', '%' . $request->search . '%')
                  ->orWhere('location',    'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('skill')) {
            $query->whereHas('skills',
                fn($q) => $q->where('skills.skill_id', $request->skill));
        }

        $jobs = $query->latest()->paginate(12)->withQueryString();

        $appliedJobIds = JobApplication::where('seeker_id', $user->id)
            ->pluck('job_id')->toArray();

        $allSkills = Skill::orderBy('skill_name')->get();

        return view('dashboard.jobseeker.jobs',
            compact('jobs', 'appliedJobIds', 'allSkills'));
    }

    // ── My Applications ───────────────────────────────────
    public function applications()
    {
        $user = Auth::user();
        if (!$user->isJobSeeker()) abort(403);

        $applications = JobApplication::with(['job.skills', 'job.shifts', 'job.owner'])
            ->where('seeker_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard.jobseeker.applications',
            compact('applications'));
    }

    // ── Profile ───────────────────────────────────────────
    public function profile()
    {
        $user = Auth::user();
        if (!$user->isJobSeeker()) abort(403);

        $skills           = Skill::orderBy('skill_name')->get();
        $selectedSkillIds = $user->skills->pluck('skill_id')->toArray();
        $availabilities   = $user->availabilities()
            ->orderBy('available_date')
            ->orderBy('available_time')
            ->get();
        $profile = $user->jobSeekerProfile;

        return view('dashboard.jobseeker.profile',
            compact('user', 'skills', 'selectedSkillIds',
                    'availabilities', 'profile'));
    }
}