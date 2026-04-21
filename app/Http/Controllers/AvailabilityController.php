<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    /**
     * Store a new availability slot.
     */
    public function store(Request $request)
    {
        // 1. Validate the input
        $validated = $request->validate([
            'available_date' => 'required|date|after_or_equal:today',
            'available_time' => 'required|date_format:H:i',
        ]);

        // 2. Check if the exact slot already exists to prevent SQL errors from the unique constraint
        $exists = Availability::where('seeker_id', Auth::id())
            ->where('available_date', $validated['available_date'])
            ->where('available_time', $validated['available_time'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'You are already marked as available at this date and time.']);
        }

        // 3. Create the record attached to the authenticated user
        Auth::user()->availabilities()->create($validated);

        return back()->with('success', 'Availability added successfully!');
    }

    /**
     * Delete an availability slot.
     */
    public function destroy($id)
    {
        $availability = Availability::findOrFail($id);

        // Security check: Ensure the user owns this record before deleting
        if ($availability->seeker_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $availability->delete();

        return back()->with('success', 'Availability removed.');
    }
}