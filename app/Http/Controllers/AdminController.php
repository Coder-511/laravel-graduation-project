<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->isAdmin()) abort(403);

        $stats = [
            'totalUsers'   => User::where('user_type', '!=', 'Admin')->count(),
            'jobOwners'    => User::where('user_type', 'JobOwner')->count(),
            'jobSeekers'   => User::where('user_type', 'JobSeeker')->count(),
            'totalJobs'    => Job::count(),
            'pendingJobs'  => Job::where('status', 'Pending')->count(),
            'approvedJobs' => Job::where('status', 'Approved')->count(),
            'rejectedJobs' => Job::where('status', 'Rejected')->count(),
            'totalSkills'  => Skill::count(),
            'recentJobs'   => Job::with('owner')->latest()->take(6)->get(),
            'recentUsers'  => User::where('user_type', '!=', 'Admin')->latest()->take(6)->get(),
        ];

        return view('dashboard.admin.dashboard', compact('stats'));
    }

    public function profile()
    {
        if (!Auth::user()->isAdmin()) abort(403);
        return view('dashboard.admin.profile', ['user' => Auth::user()]);
    }
}