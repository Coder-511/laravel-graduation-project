<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller {
    // ── Admin: list all skills ─────────────────────────────
    public function index(Request $request) {
        if (!Auth::user()->isAdmin()) abort(403);

        $search = $request->query('search');

        $skills = Skill::orderBy('skill_name')
            ->when($search, fn($q) => $q->where('skill_name', 'like', '%' . $search . '%'))
            ->get();

        return view('skills.index', compact('skills', 'search'));
    }

    // ── Admin: create a skill ──────────────────────────────
    public function store(Request $request) {
        if (!Auth::user()->isAdmin()) abort(403);

        $data = $request->validate([
            'skill_name' => [
                'required',
                'string',
                'max:100',
                'unique:skills,skill_name',
            ],
        ]);

        Skill::create($data);

        return redirect()->route('skills.index')
                         ->with('success', 'Skill added successfully.');
    }

    // ── Admin: update a skill ──────────────────────────────
    public function update(Request $request, $id) {
        if (!Auth::user()->isAdmin()) abort(403);

        $skill = Skill::findOrFail($id);

        $data = $request->validate([
            'skill_name' => [
                'required',
                'string',
                'max:100',
                'unique:skills,skill_name,' . $skill->skill_id . ',skill_id',
            ],
        ]);

        $skill->update($data);

        return redirect()->route('skills.index')
                         ->with('success', 'Skill updated successfully.');
    }

    // ── Admin: delete a skill ──────────────────────────────
    public function destroy($id) {
        if (!Auth::user()->isAdmin()) abort(403);

        $skill = Skill::findOrFail($id);
        $skill->delete();

        return redirect()->route('skills.index')
                         ->with('success', 'Skill deleted successfully.');
    }

    // ── Seeker: sync their skills ──────────────────────────
    public function syncSeekerSkills(Request $request) {
        $user = Auth::user();

        if (!$user->isJobSeeker()) abort(403);

        $data = $request->validate([
            'skill_ids'   => ['nullable', 'array'],
            'skill_ids.*' => ['integer', 'exists:skills,skill_id'],
        ]);

        // sync() removes old ones and adds new ones in one query
        $user->skills()->sync($data['skill_ids'] ?? []);

        return redirect()->back()->with('success', 'Skills updated successfully.');
    }
}