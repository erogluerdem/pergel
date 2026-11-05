<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\Project;
use Illuminate\Http\Request;

class HRController extends Controller
{
    public function dashboard()
    {
        // KPI'lar
        $totalEmployees = Employee::count();
        $assignedEmployees = Employee::whereNotNull('current_project_id')->count();
        $idleEmployees = Employee::whereNull('current_project_id')->count();
        $expiringCertificates = Certificate::where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->count();

        // Projelere göre personel dağılımı
        $projectDistribution = Employee::whereNotNull('current_project_id')
            ->with('currentProject')
            ->get()
            ->groupBy('current_project.name')
            ->map(function ($employees, $projectName) {
                return [
                    'project' => $projectName,
                    'count' => $employees->count()
                ];
            })
            ->values();

        return view('resources.hr.dashboard', compact(
            'totalEmployees',
            'assignedEmployees',
            'idleEmployees',
            'expiringCertificates',
            'projectDistribution'
        ));
    }

    public function workforcePlanning()
    {
        $employees = Employee::with('assignments.project')->get();
        $projects = Project::all();

        return view('resources.hr.workforce-planning', compact('employees', 'projects'));
    }

    public function employeeList(Request $request)
    {
        $query = Employee::with('currentProject');

        // Filtreler
        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->position . '%');
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('project')) {
            $query->where('current_project_id', $request->project);
        }

        $employees = $query->paginate(20);
        $projects = Project::all();

        return view('resources.hr.employee-list', compact('employees', 'projects'));
    }

    public function employeeProfile($id)
    {
        $employee = Employee::with(['assignments.project', 'certificates'])->findOrFail($id);

        return view('resources.hr.employee-profile', compact('employee'));
    }

    public function certificates()
    {
        $certificates = Certificate::with('employee')
            ->orderBy('expiry_date')
            ->paginate(20);

        return view('resources.hr.certificates', compact('certificates'));
    }

    public function apiGetAssignments()
    {
        $assignments = EmployeeAssignment::with(['employee', 'project'])
            ->where('status', 'active')
            ->get()
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->employee->full_name . ' - ' . $assignment->project->name,
                    'start' => $assignment->start_date,
                    'end' => $assignment->end_date ?? $assignment->start_date->addDays(30), // Default 30 days if no end date
                    'employee_id' => $assignment->employee_id,
                    'project_id' => $assignment->project_id,
                ];
            });

        return response()->json($assignments);
    }

    public function apiAssignEmployee(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'project_id' => 'required|exists:projects,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        EmployeeAssignment::create([
            'employee_id' => $request->employee_id,
            'project_id' => $request->project_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
        ]);

        // Update employee's current project
        $employee = Employee::find($request->employee_id);
        $employee->update(['current_project_id' => $request->project_id]);

        return response()->json(['success' => true]);
    }
}
