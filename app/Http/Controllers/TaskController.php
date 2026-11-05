<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Task;
use App\Models\User;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\NotificationService;

class TaskController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Display a listing of tasks.
     */
    public function index(Request $request): View
    {
        // Check permissions safely
        if (method_exists(auth()->user(), 'hasPermission') && !auth()->user()->hasPermission('tasks.view')) {
            abort(403, 'Bu sayfayı görme yetkiniz yok.');
        }

        $query = Task::with(['assignedTo', 'creator']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort by (whitelisted)
        $allowedSortColumns = ['id', 'title', 'priority', 'status', 'due_date', 'assigned_to', 'project_id', 'created_at', 'updated_at'];
        $sortBy = $request->get('sort_by', 'due_date');
        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'due_date';
        }
        $sortDirection = strtolower($request->get('sort_direction', $request->get('sort_order', 'asc')));
        if (!in_array($sortDirection, ['asc', 'desc'], true)) {
            $sortDirection = 'asc';
        }
        $query->orderBy($sortBy, $sortDirection);

        $tasks = $query->paginate(20);

        // Calculate statistics
        $stats = [
            'total' => Task::count(),
            'active' => Task::where('status', 'active')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'urgent' => Task::where('priority', 'urgent')->count(),
        ];

        // Get users for assignment dropdown
        $users = User::select('id', 'name')->get();

        return view('tasks.index', compact('tasks', 'stats', 'users'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        // Get users for assignment dropdown
        $users = User::select('id', 'name')->get();

        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'created_by' => auth()->id(),
            'status' => 'pending'
        ]);

        // Send notification to assigned user
        $this->notificationService->taskAssigned($task);

        return redirect()->route('tasks.index')
            ->with('success', 'Görev başarıyla oluşturuldu.');
    }

    /**
     * Display the specified task.
     */
    public function show(string $id): View
    {
        $task = Task::with(['assignedUser', 'createdByUser'])
                   ->findOrFail($id);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(string $id): View
    {
        $task = Task::findOrFail($id);
        $users = User::select('id', 'name')->get();

        return view('tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $task = Task::findOrFail($id);
        $oldAssignedTo = $task->assigned_to;
        
        $task->update($request->validated());

        // Send notification if assignment changed
        if ($oldAssignedTo != $request->assigned_to) {
            $this->notificationService->taskAssigned($task);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Görev başarıyla güncellendi.');
    }

    /**
     * Remove the specified task.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Görev başarıyla silindi.');
    }

    /**
     * Display Kanban board for tasks.
     */
    public function kanban(): View
    {
        // Get tasks organized by status
        $tasks = [
            'pending' => Task::with('assignedUser')->where('status', 'pending')->get(),
            'active' => Task::with('assignedUser')->where('status', 'active')->get(), 
            'review' => Task::with('assignedUser')->where('status', 'review')->get(),
            'completed' => Task::with('assignedUser')->where('status', 'completed')->get(),
        ];

        return view('tasks.kanban', compact('tasks'));
    }
}
