<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobShiftController extends Controller {
    // ──────────────────────────────────────────────────────
    // Helper — load job and verify ownership
    // ──────────────────────────────────────────────────────
    private function authorizeJobOwner(int $jobId): Job {
        $job  = Job::findOrFail($jobId);
        $user = Auth::user();

        // Only the person who posted the job can manage its shifts
        if ($job->owner_id !== $user->id) {
            abort(403, 'You can only manage shifts for jobs you posted.');
        }

        return $job;
    }

    // ──────────────────────────────────────────────────────
    // LIST shifts for a job
    // ──────────────────────────────────────────────────────
    public function index(int $jobId) {
        $job    = $this->authorizeJobOwner($jobId);
        $shifts = $job->shifts()->get(); // already ordered by date then start (via model)

        return view('shifts.index', compact('job', 'shifts'));
    }

    // ──────────────────────────────────────────────────────
    // STORE a new shift
    // ──────────────────────────────────────────────────────
    public function store(Request $request, int $jobId) {
        $job = $this->authorizeJobOwner($jobId);

        $data = $request->validate([
            'shift_date'  => ['required', 'date'],  // ← no after_or_equal here
            'shift_start' => ['required', 'date_format:H:i'],
            'shift_end'   => ['required', 'date_format:H:i', 'after:shift_start'],
        ], [
            'shift_end.after' => 'Shift end time must be after shift start time.',
        ]);

        $overlap = JobShift::where('job_id', $job->job_id)
            ->where('shift_date', $data['shift_date'])
            ->where(function ($q) use ($data) {
                $q->where('shift_start', '<', $data['shift_end'])
                ->where('shift_end', '>', $data['shift_start']);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'shift_start' => 'Shift overlaps with existing shift.'
            ]);
        }

        $job->shifts()->create($data);

        return redirect()->back()->with('success', 'Shift added successfully.');
    }

    // ──────────────────────────────────────────────────────
    // UPDATE an existing shift
    // ──────────────────────────────────────────────────────
    public function update(Request $request, int $jobId, int $shiftId) {
        $job   = $this->authorizeJobOwner($jobId);
        $shift = JobShift::where('shift_id', $shiftId)
                         ->where('job_id', $job->job_id)
                         ->firstOrFail(); // ensures shift belongs to this job

        $data = $request->validate([
            'shift_date' => ['required', 'date'],
            'shift_start' => ['required', 'date_format:H:i'],
            'shift_end'   => ['required', 'date_format:H:i', 'after:shift_start'],
        ], [
            'shift_end.after'           => 'Shift end time must be after shift start time.',
        ]);

        $overlap = JobShift::where('job_id', $job->job_id)
            ->where('shift_date', $data['shift_date'])
            ->where('shift_id', '!=', $shiftId) // exclude the shift being edited
            ->where(function ($q) use ($data) {
                $q->where('shift_start', '<', $data['shift_end'])
                ->where('shift_end', '>', $data['shift_start']);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'shift_start' => 'Shift overlaps with an existing shift.'
            ]);
        }

        $shift->update($data);

        return redirect()->back()->with('success', 'Shift updated successfully.');
    }

    // ──────────────────────────────────────────────────────
    // DELETE a shift
    // ──────────────────────────────────────────────────────
    public function destroy(int $jobId, int $shiftId) {
        $job   = $this->authorizeJobOwner($jobId);
        $shift = JobShift::where('shift_id', $shiftId)
                         ->where('job_id', $job->job_id)
                         ->firstOrFail();

        $shift->delete();

        return redirect()->back()->with('success', 'Shift deleted successfully.');
    }
}