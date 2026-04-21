<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    // ─── Job Seeker: Apply to a job ──────────────────────────────

    public function apply(Request $request, int $jobId): JsonResponse
    {
        $user = Auth::user();

        if ($user->user_type !== 'JobSeeker') {
            return response()->json(['message' => 'Only job seekers can apply.'], 403);
        }

        $job = Job::findOrFail($jobId);

        if ($job->status !== 'Approved') {
            return response()->json(['message' => 'This job is not available.'], 422);
        }

        if ($job->owner_id === $user->id) {
            return response()->json(['message' => 'You cannot apply to your own job.'], 422);
        }

        $alreadyApplied = JobApplication::where('job_id', $jobId)
            ->where('seeker_id', $user->id)
            ->exists();

        if ($alreadyApplied) {
            return response()->json(['message' => 'You have already applied to this job.'], 409);
        }

        $request->validate([
            'message' => 'nullable|string|max:1000',
        ]);

        $application = JobApplication::create([
            'job_id'    => $jobId,
            'seeker_id' => $user->id,
            'message'   => $request->message,
            'status'    => 'Pending',
        ]);

        // ── Notify the job owner ──
        Notification::notify(
            $job->owner_id,
            'New Application Received',
            "{$user->name} has applied to your job \"{$job->title}\"."
        );

        return response()->json([
            'message'     => 'Application submitted successfully.',
            'application' => $application,
        ], 201);
    }

    // ─── Job Owner: View all applications across their jobs ──────

    public function ownerIndex(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->user_type !== 'JobOwner') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $applications = JobApplication::with(['job', 'seeker'])
            ->whereHas('job', fn($q) => $q->where('owner_id', $user->id))
            ->latest()
            ->get();

        return response()->json($applications);
    }

    // ─── Job Owner: View applications for a specific job ─────────

    public function jobApplications(int $jobId): JsonResponse
    {
        $user = Auth::user();
        $job  = Job::findOrFail($jobId);

        if ($user->user_type !== 'JobOwner' || $job->owner_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $applications = JobApplication::with('seeker')
            ->where('job_id', $jobId)
            ->latest()
            ->get();

        return response()->json($applications);
    }

    // ─── Job Owner: Accept or Reject an application ──────────────

    public function updateStatus(Request $request, int $applicationId): JsonResponse
    {
        $user = Auth::user();

        if ($user->user_type !== 'JobOwner') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'status' => 'required|in:Accepted,Rejected',
        ]);

        $application = JobApplication::with('job')->findOrFail($applicationId);

        if ($application->job->owner_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($application->status !== 'Pending') {
            return response()->json(['message' => 'This application has already been reviewed.'], 422);
        }

        $application->update(['status' => $request->status]);

        // ── Notify the job seeker ──
        if ($request->status === 'Accepted') {
            Notification::notify(
                $application->seeker_id,
                'Application Accepted!',
                "Congratulations! Your application for \"{$application->job->title}\" has been accepted."
            );
        } else {
            Notification::notify(
                $application->seeker_id,
                'Application Rejected',
                "Unfortunately, your application for \"{$application->job->title}\" was not accepted."
            );
        }

        return response()->json([
            'message'     => "Application {$request->status}.",
            'application' => $application,
        ]);
    }

    // ─── Job Seeker: Cancel their own application ────────────────

    public function cancel(int $applicationId): JsonResponse
    {
        $user        = Auth::user();
        $application = JobApplication::findOrFail($applicationId);

        if ($application->seeker_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($application->status !== 'Pending') {
            return response()->json(['message' => 'Only pending applications can be canceled.'], 422);
        }

        $application->update(['status' => 'Canceled']);

        return response()->json(['message' => 'Application canceled.']);
    }

    // ─── Job Seeker: View their own applications ─────────────────

    public function myApplications(): JsonResponse
    {
        $user = Auth::user();

        if ($user->user_type !== 'JobSeeker') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $applications = JobApplication::with('job')
            ->where('seeker_id', $user->id)
            ->latest()
            ->get();

        return response()->json($applications);
    }
}