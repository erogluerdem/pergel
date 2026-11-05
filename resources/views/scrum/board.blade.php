@extends('layouts.master')

@section('title', 'Scrum Board')

@section('topbar-title')
    <div class="page-title-box">
        <h4 class="page-title">
            <i class="fas fa-columns me-2"></i>Scrum Board
        </h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Scrum Board</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Sprint Information -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-play-circle text-success me-2"></i>
                                {{ $sprints[0]->name }}
                                <span class="badge bg-success ms-2">{{ ucfirst($sprints[0]->status) }}</span>
                            </h5>
                            <p class="text-muted mb-0">
                                {{ \Carbon\Carbon::parse($sprints[0]->start_date)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($sprints[0]->end_date)->format('d M Y') }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="text-end">
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ ($sprintStats['completed_points'] / $sprintStats['total_points']) * 100 }}%">
                                        {{ $sprintStats['completed_points'] }}/{{ $sprintStats['total_points'] }} pts
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $sprintStats['days_remaining'] }} gün kaldı • Velocity: {{ $sprintStats['velocity'] }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scrum Board -->
    <div class="row">
        <!-- Backlog Column -->
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-inbox me-2"></i>Backlog
                        <span class="badge bg-light text-dark ms-2">{{ count($stories['backlog']) }}</span>
                    </h6>
                </div>
                <div class="card-body p-2" style="max-height: 600px; overflow-y: auto;">
                    @foreach($stories['backlog'] as $story)
                        <div class="card mb-2 story-card" data-story-id="{{ $story->id }}">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-{{ $story->priority == 'high' ? 'danger' : ($story->priority == 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($story->priority) }}
                                    </span>
                                    <span class="badge bg-primary">{{ $story->story_points }} pts</span>
                                </div>
                                <h6 class="card-title mb-2 small">{{ $story->title }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $story->assignee }}</small>
                                    <i class="fas fa-{{ $story->type == 'feature' ? 'star' : ($story->type == 'bug' ? 'bug' : 'arrow-up') }} text-muted"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- To Do Column -->
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>To Do
                        <span class="badge bg-light text-dark ms-2">{{ count($stories['todo']) }}</span>
                    </h6>
                </div>
                <div class="card-body p-2" style="max-height: 600px; overflow-y: auto;">
                    @foreach($stories['todo'] as $story)
                        <div class="card mb-2 story-card" data-story-id="{{ $story->id }}">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-{{ $story->priority == 'high' ? 'danger' : ($story->priority == 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($story->priority) }}
                                    </span>
                                    <span class="badge bg-primary">{{ $story->story_points }} pts</span>
                                </div>
                                <h6 class="card-title mb-2 small">{{ $story->title }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $story->assignee }}</small>
                                    <i class="fas fa-{{ $story->type == 'feature' ? 'star' : ($story->type == 'bug' ? 'bug' : 'arrow-up') }} text-muted"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-spinner me-2"></i>In Progress
                        <span class="badge bg-light text-dark ms-2">{{ count($stories['in_progress']) }}</span>
                    </h6>
                </div>
                <div class="card-body p-2" style="max-height: 600px; overflow-y: auto;">
                    @foreach($stories['in_progress'] as $story)
                        <div class="card mb-2 story-card" data-story-id="{{ $story->id }}">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-{{ $story->priority == 'high' ? 'danger' : ($story->priority == 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($story->priority) }}
                                    </span>
                                    <span class="badge bg-primary">{{ $story->story_points }} pts</span>
                                </div>
                                <h6 class="card-title mb-2 small">{{ $story->title }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $story->assignee }}</small>
                                    <i class="fas fa-{{ $story->type == 'feature' ? 'star' : ($story->type == 'bug' ? 'bug' : 'arrow-up') }} text-muted"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Testing Column -->
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-vial me-2"></i>Testing
                        <span class="badge bg-light text-dark ms-2">{{ count($stories['testing']) }}</span>
                    </h6>
                </div>
                <div class="card-body p-2" style="max-height: 600px; overflow-y: auto;">
                    @foreach($stories['testing'] as $story)
                        <div class="card mb-2 story-card" data-story-id="{{ $story->id }}">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-{{ $story->priority == 'high' ? 'danger' : ($story->priority == 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($story->priority) }}
                                    </span>
                                    <span class="badge bg-primary">{{ $story->story_points }} pts</span>
                                </div>
                                <h6 class="card-title mb-2 small">{{ $story->title }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $story->assignee }}</small>
                                    <i class="fas fa-{{ $story->type == 'feature' ? 'star' : ($story->type == 'bug' ? 'bug' : 'arrow-up') }} text-muted"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Done Column -->
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-check me-2"></i>Done
                        <span class="badge bg-light text-dark ms-2">{{ count($stories['done']) }}</span>
                    </h6>
                </div>
                <div class="card-body p-2" style="max-height: 600px; overflow-y: auto;">
                    @foreach($stories['done'] as $story)
                        <div class="card mb-2 story-card" data-story-id="{{ $story->id }}">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-{{ $story->priority == 'high' ? 'danger' : ($story->priority == 'medium' ? 'warning' : 'info') }}">
                                        {{ ucfirst($story->priority) }}
                                    </span>
                                    <span class="badge bg-primary">{{ $story->story_points }} pts</span>
                                </div>
                                <h6 class="card-title mb-2 small">{{ $story->title }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $story->assignee }}</small>
                                    <i class="fas fa-{{ $story->type == 'feature' ? 'star' : ($story->type == 'bug' ? 'bug' : 'arrow-up') }} text-check text-success"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Team Info Column -->
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2"></i>Team
                        <span class="badge bg-light text-dark ms-2">{{ count($teamMembers) }}</span>
                    </h6>
                </div>
                <div class="card-body p-2" style="max-height: 600px; overflow-y: auto;">
                    @foreach($teamMembers as $member)
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1 small">{{ $member->name }}</h6>
                                <small class="text-muted d-block mb-2">{{ $member->role }}</small>
                                <div class="progress mb-1" style="height: 8px;">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                         style="width: {{ ($member->capacity / 40) * 100 }}%">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $member->capacity }}h/week</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.story-card {
    cursor: move;
    transition: all 0.3s ease;
}

.story-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card-header h6 {
    font-weight: 600;
}

.progress {
    border-radius: 10px;
}

.badge {
    font-size: 0.7em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Drag and drop functionality can be added here
    console.log('Scrum Board loaded');
    
    // Add click handlers for story cards
    document.querySelectorAll('.story-card').forEach(card => {
        card.addEventListener('click', function() {
            const storyId = this.dataset.storyId;
            console.log('Story clicked:', storyId);
            // Story detail modal can be opened here
        });
    });
});
</script>
@endsection