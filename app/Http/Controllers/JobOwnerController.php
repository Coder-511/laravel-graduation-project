<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;

class JobOwnerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->isJobOwner()) abort(403);

        $jobs = Job::where('owner_id', $user->id)->get();

        $stats = [
            'totalJobs'        => $jobs->count(),
            'approvedJobs'     => $jobs->where('status', 'Approved')->count(),
            'pendingJobs'      => $jobs->where('status', 'Pending')->count(),
            'rejectedJobs'     => $jobs->where('status', 'Rejected')->count(),
            'totalApplications'=> JobApplication::whereHas('job', fn($q) =>
                                      $q->where('owner_id', $user->id))->count(),
            'pendingApplications' => JobApplication::whereHas('job', fn($q) =>
                                         $q->where('owner_id', $user->id))
                                         ->where('status', 'Pending')->count(),
            'recentJobs'       => Job::with(['skills', 'shifts'])
                                      ->where('owner_id', $user->id)
                                      ->latest()->take(5)->get(),
            'recentApplications' => JobApplication::with(['job', 'seeker'])
                                        ->whereHas('job', fn($q) =>
                                            $q->where('owner_id', $user->id))
                                        ->latest()->take(5)->get(),
        ];

        return view('dashboard.jobowner.dashboard', compact('stats'));
    }

    public function profile()
    {
        if (!Auth::user()->isJobOwner()) abort(403);
        return view('dashboard.jobowner.profile', ['user' => Auth::user()]);
    }
}