<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display search results page
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category', 'all');
        $perPage = 20;
        
        $results = [];
        $totalCount = 0;
        
        if (strlen(trim($query)) >= 2) {
            $results = $this->performSearch($query, $category, $perPage);
            $totalCount = $this->getTotalResultsCount($query, $category);
        }
        
        return view('search.index', compact('query', 'category', 'results', 'totalCount'));
    }
    
    /**
     * Perform search across different models
     */
    private function performSearch($query, $category = 'all', $perPage = 20)
    {
        $results = [
            'projects' => [],
            'tasks' => [],
            'users' => [],
            'documents' => []
        ];
        
        $searchTerm = '%' . $query . '%';
        
        // Search Projects
        if ($category === 'all' || $category === 'projects') {
            $results['projects'] = Project::where('name', 'LIKE', $searchTerm)
                ->orWhere('description', 'LIKE', $searchTerm)
                ->orWhere('project_code', 'LIKE', $searchTerm)
                ->select('id', 'name', 'description', 'status', 'project_code', 'created_at')
                ->limit($category === 'projects' ? $perPage : 5)
                ->get()
                ->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'title' => $project->name,
                        'subtitle' => $project->project_code ? "Proje Kodu: {$project->project_code}" : 'Proje',
                        'description' => $project->description,
                        'status' => $project->status,
                        'url' => \Illuminate\Support\Facades\Route::has('projects.show') ? route('projects.show', $project->id) : '#',
                        'created_at' => $project->created_at,
                        'type' => 'project'
                    ];
                });
        }
        
        // Search Tasks
        if ($category === 'all' || $category === 'tasks') {
            $results['tasks'] = Task::where('title', 'LIKE', $searchTerm)
                ->orWhere('description', 'LIKE', $searchTerm)
                ->with('project:id,name')
                ->select('id', 'title', 'description', 'status', 'priority', 'project_id', 'created_at')
                ->limit($category === 'tasks' ? $perPage : 5)
                ->get()
                ->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'subtitle' => $task->project ? "Proje: {$task->project->name}" : 'Görev',
                        'description' => $task->description,
                        'status' => $task->status,
                        'priority' => $task->priority,
                        'url' => \Illuminate\Support\Facades\Route::has('tasks.show') ? route('tasks.show', $task->id) : '#',
                        'created_at' => $task->created_at,
                        'type' => 'task'
                    ];
                });
        }
        
        // Search Users
        if ($category === 'all' || $category === 'users') {
            $results['users'] = User::where('name', 'LIKE', $searchTerm)
                ->orWhere('email', 'LIKE', $searchTerm)
                ->select('id', 'name', 'email', 'created_at')
                ->limit($category === 'users' ? $perPage : 5)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'title' => $user->name,
                        'subtitle' => $user->email,
                        'description' => 'Kullanıcı',
                        'url' => \Illuminate\Support\Facades\Route::has('users.show') ? route('users.show', $user->id) : '#',
                        'created_at' => $user->created_at,
                        'type' => 'user'
                    ];
                });
        }
        
        // Search Documents (if Document model exists)
        if (($category === 'all' || $category === 'documents') && class_exists('App\Models\Document')) {
            try {
                $results['documents'] = Document::where('title', 'LIKE', $searchTerm)
                    ->orWhere('description', 'LIKE', $searchTerm)
                    ->select('id', 'title', 'description', 'file_type', 'file_size', 'created_at')
                    ->limit($category === 'documents' ? $perPage : 5)
                    ->get()
                    ->map(function ($document) {
                        return [
                            'id' => $document->id,
                            'title' => $document->title,
                            'subtitle' => $document->file_type ? strtoupper($document->file_type) . ' Dosyası' : 'Doküman',
                            'description' => $document->description,
                            'file_size' => $document->file_size,
                            'url' => \Illuminate\Support\Facades\Route::has('documents.show') ? route('documents.show', $document->id) : '#',
                            'created_at' => $document->created_at,
                            'type' => 'document'
                        ];
                    });
            } catch (\Exception $e) {
                // Document model doesn't exist or table doesn't exist
                $results['documents'] = collect([]);
            }
        }
        
        return $results;
    }
    
    /**
     * Get total count of results for pagination
     */
    private function getTotalResultsCount($query, $category = 'all')
    {
        $searchTerm = '%' . $query . '%';
        $count = 0;
        
        if ($category === 'all' || $category === 'projects') {
            $count += Project::where('name', 'LIKE', $searchTerm)
                ->orWhere('description', 'LIKE', $searchTerm)
                ->orWhere('project_code', 'LIKE', $searchTerm)
                ->count();
        }
        
        if ($category === 'all' || $category === 'tasks') {
            $count += Task::where('title', 'LIKE', $searchTerm)
                ->orWhere('description', 'LIKE', $searchTerm)
                ->count();
        }
        
        if ($category === 'all' || $category === 'users') {
            $count += User::where('name', 'LIKE', $searchTerm)
                ->orWhere('email', 'LIKE', $searchTerm)
                ->count();
        }
        
        if (($category === 'all' || $category === 'documents') && class_exists('App\Models\Document')) {
            try {
                $count += Document::where('title', 'LIKE', $searchTerm)
                    ->orWhere('description', 'LIKE', $searchTerm)
                    ->count();
            } catch (\Exception $e) {
                // Document model doesn't exist
            }
        }
        
        return $count;
    }
}