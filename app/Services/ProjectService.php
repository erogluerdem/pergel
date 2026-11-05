<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * ProjectService - Core business logic for project operations
 * 
 * Handles project calculations, status determinations, and complex queries
 * to keep controllers thin and business logic centralized.
 */
class ProjectService
{
    /**
     * Get filtered projects with pagination
     * 
     * @param array $filters Filter criteria
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     */
    public function getFilteredProjects(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Project::query();

        // Search
        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%")
                   ->orWhere('location', 'like', "%{$q}%")
                   ->orWhere('project_code', 'like', "%{$q}%");
            });
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Type filter
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Project manager filter
        if (!empty($filters['manager'])) {
            $query->where('project_manager', $filters['manager']);
        }

        // Region filter
        if (!empty($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        // Health status filter
        if (!empty($filters['health'])) {
            $query->where('health_status', $filters['health']);
        }

        // Date range filter
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $startDate = Carbon::parse($filters['start_date'])->startOfDay();
            $endDate = Carbon::parse($filters['end_date'])->endOfDay();
            $query->whereBetween('start_date', [$startDate, $endDate]);
        }

        // Budget range filter
        if (!empty($filters['min_budget']) || !empty($filters['max_budget'])) {
            if (!empty($filters['min_budget'])) {
                $query->where('budget', '>=', $filters['min_budget']);
            }
            if (!empty($filters['max_budget'])) {
                $query->where('budget', '<=', $filters['max_budget']);
            }
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Calculate project health status based on multiple factors
     * 
     * @param Project $project
     * @return string Health status: 'healthy', 'risky', 'critical'
     */
    public function calculateHealthStatus(Project $project): string
    {
        $riskFactors = 0;
        $maxRiskFactors = 5;

        // Budget overrun risk
        if ($project->budget && $project->spent) {
            $budgetUtilization = ($project->spent / $project->budget) * 100;
            if ($budgetUtilization > 90) {
                $riskFactors += 2; // High priority risk
            } elseif ($budgetUtilization > 75) {
                $riskFactors += 1;
            }
        }

        // Timeline risk
        if ($project->start_date && $project->end_date) {
            $totalDays = $project->start_date->diffInDays($project->end_date);
            $elapsedDays = $project->start_date->diffInDays(now());
            
            if ($totalDays > 0) {
                $timeProgress = ($elapsedDays / $totalDays) * 100;
                $scheduleProgress = $project->progress_percent ?? 0;
                $scheduleVariance = $timeProgress - $scheduleProgress;

                if ($scheduleVariance > 20) {
                    $riskFactors += 2; // Behind schedule
                } elseif ($scheduleVariance > 10) {
                    $riskFactors += 1;
                }
            }

            // Overdue projects
            if ($project->end_date < now() && $project->status !== 'completed') {
                $riskFactors += 2;
            }
        }

        // Project completion risk
        if ($project->progress_percent) {
            $progress = $project->progress_percent;
            if ($progress === 0 && $project->status === 'construction') {
                $riskFactors += 1;
            }
        }

        // Determine health status
        $riskScore = ($riskFactors / $maxRiskFactors) * 100;

        if ($riskScore >= 60) {
            return 'critical';
        } elseif ($riskScore >= 30) {
            return 'risky';
        }

        return 'healthy';
    }

    /**
     * Calculate project budget utilization percentage
     * 
     * @param Project $project
     * @return float Budget utilization percentage (0-100)
     */
    public function calculateBudgetUtilization(Project $project): float
    {
        if (!$project->budget || $project->budget == 0) {
            return 0;
        }

        $spent = $project->spent ?? 0;
        return ($spent / $project->budget) * 100;
    }

    /**
     * Calculate project timeline progress
     * 
     * @param Project $project
     * @return float Timeline progress percentage (0-100)
     */
    public function calculateTimelineProgress(Project $project): float
    {
        if (!$project->start_date || !$project->end_date) {
            return 0;
        }

        $totalDays = $project->start_date->diffInDays($project->end_date);
        if ($totalDays <= 0) {
            return 100;
        }

        $elapsedDays = $project->start_date->diffInDays(now());
        $progress = ($elapsedDays / $totalDays) * 100;

        return min($progress, 100);
    }

    /**
     * Check if project is overdue
     * 
     * @param Project $project
     * @return bool
     */
    public function isOverdue(Project $project): bool
    {
        return $project->end_date && $project->end_date < now() && $project->status !== 'completed';
    }

    /**
     * Calculate days remaining in project
     * 
     * @param Project $project
     * @return int|null Days remaining or null if project has no end date
     */
    public function getDaysRemaining(Project $project): ?int
    {
        if (!$project->end_date) {
            return null;
        }

        $remaining = now()->diffInDays($project->end_date, false);
        return max($remaining, 0);
    }

    /**
     * Get project summary with key metrics
     * 
     * @param Project $project
     * @return array Project summary data
     */
    public function getProjectSummary(Project $project): array
    {
        $budgetUtilization = $this->calculateBudgetUtilization($project);
        $timelineProgress = $this->calculateTimelineProgress($project);
        $healthStatus = $this->calculateHealthStatus($project);

        return [
            'id' => $project->id,
            'name' => $project->name,
            'code' => $project->project_code,
            'status' => $project->status,
            'health' => $healthStatus,
            'progress' => $project->progress_percent ?? 0,
            'budget' => [
                'total' => $project->budget,
                'spent' => $project->spent,
                'remaining' => $project->budget ? ($project->budget - ($project->spent ?? 0)) : 0,
                'utilization' => round($budgetUtilization, 2),
            ],
            'timeline' => [
                'start' => $project->start_date,
                'end' => $project->end_date,
                'progress' => round($timelineProgress, 2),
                'remaining_days' => $this->getDaysRemaining($project),
                'is_overdue' => $this->isOverdue($project),
            ],
            'manager' => $project->project_manager,
            'location' => $project->location,
        ];
    }

    /**
     * Get project collection summary
     * 
     * @param Collection|array $projects
     * @return array Summary statistics
     */
    public function getProjectsCollectionSummary(Collection|array $projects): array
    {
        if (!$projects instanceof Collection) {
            $projects = collect($projects);
        }
        $totalProjects = $projects->count();
        $activeProjects = $projects->where('status', '!=', 'completed')->count();
        $completedProjects = $projects->where('status', '=', 'completed')->count();
        $overdueProjects = $projects->filter(fn($p) => $this->isOverdue($p))->count();

        $totalBudget = $projects->sum('budget');
        $totalSpent = $projects->sum('spent');
        $budgetUtilization = $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0;

        $healthStatusCounts = [
            'healthy' => $projects->filter(fn($p) => $this->calculateHealthStatus($p) === 'healthy')->count(),
            'risky' => $projects->filter(fn($p) => $this->calculateHealthStatus($p) === 'risky')->count(),
            'critical' => $projects->filter(fn($p) => $this->calculateHealthStatus($p) === 'critical')->count(),
        ];

        return [
            'total_projects' => $totalProjects,
            'active_projects' => $activeProjects,
            'completed_projects' => $completedProjects,
            'overdue_projects' => $overdueProjects,
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'remaining_budget' => $totalBudget - $totalSpent,
            'budget_utilization' => round($budgetUtilization, 2),
            'health_status' => $healthStatusCounts,
        ];
    }

    /**
     * Get projects by status
     * 
     * @param string $status
     * @return Collection
     */
    public function getProjectsByStatus(string $status): Collection
    {
        return Project::where('status', $status)->get();
    }

    /**
     * Get projects due within days
     * 
     * @param int $days
     * @return Collection
     */
    public function getProjectsDueWithinDays(int $days = 30): Collection
    {
        $startDate = now();
        $endDate = now()->addDays($days);

        return Project::whereBetween('end_date', [$startDate, $endDate])
            ->where('status', '!=', 'completed')
            ->orderBy('end_date', 'asc')
            ->get();
    }

    /**
     * Get overdue projects
     * 
     * @return Collection
     */
    public function getOverdueProjects(): Collection
    {
        return Project::where('end_date', '<', now())
            ->where('status', '!=', 'completed')
            ->orderBy('end_date', 'asc')
            ->get();
    }

    /**
     * Check for critical projects needing attention
     * 
     * @return Collection
     */
    public function getCriticalProjects(): Collection
    {
        return Project::where('status', '!=', 'completed')
            ->where(function ($query) {
                // Budget risk
                $query->whereRaw('COALESCE(spent, 0) > COALESCE(budget, 0) * 0.9')
                      // Timeline risk
                      ->orWhere('end_date', '<', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
