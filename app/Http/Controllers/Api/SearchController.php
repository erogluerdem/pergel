<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class SearchController extends Controller
{
    /**
     * Perform API search for autocomplete/dropdown
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 3); // Limit per category for dropdown
        
        if (strlen(trim($query)) < 2) {
            return Response::json([
                'success' => true,
                'results' => [
                    'projects' => [],
                    'tasks' => [],
                    'users' => [],
                    'documents' => []
                ],
                'total' => 0
            ]);
        }
        
        $results = $this->performSearch($query, $limit);
        $total = array_sum(array_map('count', $results));
        
        return Response::json([
            'success' => true,
            'results' => $results,
            'total' => $total,
            'query' => $query
        ]);
    }
    
    /**
     * Perform search across different models
     */
    private function performSearch($query, $limit = 3)
    {
        $results = [
            'projects' => [],
            'tasks' => [],
            'users' => [],
            'documents' => []
        ];
        
        $searchTerm = '%' . $query . '%';
        
        // Search Projects
        try {
            $projects = Project::where('name', 'LIKE', $searchTerm)
                ->orWhere('description', 'LIKE', $searchTerm)
                ->orWhere('project_code', 'LIKE', $searchTerm)
                ->select('id', 'name', 'description', 'status', 'project_code')
                ->limit($limit)
                ->get();
                
            $results['projects'] = $projects->map(function ($project) {
                $status = $project->status ?? 'unknown';
                $statusText = $this->getStatusText($status, 'project');
                
                return [
                    'id' => $project->id,
                    'title' => $project->name,
                    'subtitle' => $project->project_code ? "Kod: {$project->project_code} • {$statusText}" : $statusText,
                    'url' => route('projects.show', $project->id),
                    'type' => 'project',
                    'status' => $status
                ];
            })->toArray();
        } catch (\Exception $e) {
            $results['projects'] = [];
        }
        
        // Search Tasks
        try {
            $tasks = Task::where('title', 'LIKE', $searchTerm)
                ->orWhere('description', 'LIKE', $searchTerm)
                ->with('project:id,name')
                ->select('id', 'title', 'description', 'status', 'priority', 'project_id')
                ->limit($limit)
                ->get();
                
            $results['tasks'] = $tasks->map(function ($task) {
                $status = $task->status ?? 'unknown';
                $priority = $task->priority ?? 'normal';
                $statusText = $this->getStatusText($status, 'task');
                $priorityText = $this->getPriorityText($priority);
                
                $subtitle = $statusText;
                if ($task->project) {
                    $subtitle .= " • {$task->project->name}";
                }
                if ($priority !== 'normal') {
                    $subtitle .= " • {$priorityText}";
                }
                
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'subtitle' => $subtitle,
                    'url' => route('tasks.show', $task->id),
                    'type' => 'task',
                    'status' => $status,
                    'priority' => $priority
                ];
            })->toArray();
        } catch (\Exception $e) {
            $results['tasks'] = [];
        }
        
        // Search Users
        try {
            $users = User::where('name', 'LIKE', $searchTerm)
                ->orWhere('email', 'LIKE', $searchTerm)
                ->select('id', 'name', 'email')
                ->limit($limit)
                ->get();
                
            $results['users'] = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'title' => $user->name,
                    'subtitle' => $user->email,
                    'url' => \Illuminate\Support\Facades\Route::has('users.show') ? route('users.show', $user->id) : '#',
                    'type' => 'user'
                ];
            })->toArray();
        } catch (\Exception $e) {
            $results['users'] = [];
        }
        
        // Search Documents (if Document model exists)
        if (class_exists('App\Models\Document')) {
            try {
                $documents = Document::where('title', 'LIKE', $searchTerm)
                    ->orWhere('description', 'LIKE', $searchTerm)
                    ->select('id', 'title', 'description', 'file_type', 'file_size')
                    ->limit($limit)
                    ->get();
                    
                $results['documents'] = $documents->map(function ($document) {
                    $subtitle = 'Doküman';
                    if ($document->file_type) {
                        $subtitle = strtoupper($document->file_type) . ' Dosyası';
                    }
                    if ($document->file_size) {
                        $subtitle .= ' • ' . $this->formatFileSize($document->file_size);
                    }
                    
                    return [
                        'id' => $document->id,
                        'title' => $document->title,
                        'subtitle' => $subtitle,
                        'url' => route('documents.show', $document->id),
                        'type' => 'document'
                    ];
                })->toArray();
            } catch (\Exception $e) {
                $results['documents'] = [];
            }
        }
        
        return $results;
    }
    
    /**
     * Get human readable status text
     */
    private function getStatusText($status, $type = 'project')
    {
        $statusMap = [
            'project' => [
                'active' => 'Aktif',
                'completed' => 'Tamamlandı',
                'on_hold' => 'Beklemede',
                'cancelled' => 'İptal Edildi',
                'planning' => 'Planlama'
            ],
            'task' => [
                'todo' => 'Yapılacak',
                'in_progress' => 'Devam Ediyor',
                'completed' => 'Tamamlandı',
                'cancelled' => 'İptal',
                'on_hold' => 'Beklemede'
            ]
        ];
        
        return $statusMap[$type][$status] ?? ucfirst($status);
    }
    
    /**
     * Get human readable priority text
     */
    private function getPriorityText($priority)
    {
        $priorityMap = [
            'low' => 'Düşük Öncelik',
            'normal' => 'Normal',
            'high' => 'Yüksek Öncelik',
            'urgent' => 'Acil'
        ];
        
        return $priorityMap[$priority] ?? ucfirst($priority);
    }
    
    /**
     * Format file size for display
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 1) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
}