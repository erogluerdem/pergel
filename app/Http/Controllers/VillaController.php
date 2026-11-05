<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Villa;
use Illuminate\Http\Request;

class VillaController extends Controller
{
    public function edit(Project $project, Villa $villa)
    {
        return view('projects.villas.edit', compact('project', 'villa'));
    }

    public function update(Request $request, Project $project, Villa $villa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_cost' => 'required|numeric|min:0',
            'status' => 'required|in:planned,in_progress,completed',
        ]);

        $villa->update($request->only(['name', 'target_cost', 'status']));

        return redirect()->route('projects.costs.index', $project)->with('success', 'Villa bilgileri güncellendi.');
    }
}
