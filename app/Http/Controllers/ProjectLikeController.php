<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectLike;

class ProjectLikeController extends Controller
{
    public function store(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // prevent duplicate likes per session
        $sessionId = session()->getId();
        $exists = ProjectLike::where('project_id', $project->id)->where('session_id', $sessionId)->exists();
        if($exists){
            return response()->json(['status' => 'exists', 'count' => $project->likes()->count()]);
        }

        $like = ProjectLike::create([
            'project_id' => $project->id,
            'session_id' => $sessionId,
            'ip' => $request->ip()
        ]);

        return response()->json(['status' => 'ok', 'count' => $project->likes()->count()]);
    }
}
