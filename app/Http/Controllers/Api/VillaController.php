<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class VillaController extends Controller
{
    public function index(Project $project)
    {
        return $project->villas->map(function ($villa) {
            return [
                'id' => $villa->id,
                'name' => $villa->name,
            ];
        });
    }
}
