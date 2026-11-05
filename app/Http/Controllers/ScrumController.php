<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ScrumController extends Controller
{
    /**
     * Display the Scrum board.
     */
    public function board(): View
    {
        $sprints = [
            (object)[
                'id' => 1,
                'name' => 'Sprint 1 - Q1 2025',
                'start_date' => '2025-01-01',
                'end_date' => '2025-01-14',
                'status' => 'active'
            ],
            (object)[
                'id' => 2,
                'name' => 'Sprint 2 - Q1 2025',
                'start_date' => '2025-01-15',
                'end_date' => '2025-01-28',
                'status' => 'planned'
            ]
        ];

        $stories = [
            'backlog' => [
                (object)[
                    'id' => 1,
                    'title' => 'Kullanıcı profil sayfası tasarımı',
                    'story_points' => 8,
                    'assignee' => 'UI/UX Takımı',
                    'priority' => 'high',
                    'type' => 'feature'
                ],
                (object)[
                    'id' => 2,
                    'title' => 'API endpoint güvenlik iyileştirmeleri',
                    'story_points' => 13,
                    'assignee' => 'Backend Takımı',
                    'priority' => 'medium',
                    'type' => 'improvement'
                ]
            ],
            'todo' => [
                (object)[
                    'id' => 3,
                    'title' => 'Proje listesi filtreleme özelliği',
                    'story_points' => 5,
                    'assignee' => 'Ahmet Yılmaz',
                    'priority' => 'medium',
                    'type' => 'feature'
                ],
                (object)[
                    'id' => 4,
                    'title' => 'Görev atama bildirimleri',
                    'story_points' => 3,
                    'assignee' => 'Ayşe Kaya',
                    'priority' => 'low',
                    'type' => 'feature'
                ]
            ],
            'in_progress' => [
                (object)[
                    'id' => 5,
                    'title' => 'Dashboard performans optimizasyonu',
                    'story_points' => 8,
                    'assignee' => 'Fatma Şen',
                    'priority' => 'high',
                    'type' => 'improvement'
                ]
            ],
            'testing' => [
                (object)[
                    'id' => 6,
                    'title' => 'Fatura oluşturma modülü',
                    'story_points' => 13,
                    'assignee' => 'Emre Yılmaz',
                    'priority' => 'high',
                    'type' => 'feature'
                ]
            ],
            'done' => [
                (object)[
                    'id' => 7,
                    'title' => 'Envanter listesi görüntüleme',
                    'story_points' => 5,
                    'assignee' => 'Zeynep Arslan',
                    'priority' => 'medium',
                    'type' => 'feature'
                ],
                (object)[
                    'id' => 8,
                    'title' => 'Kullanıcı giriş hata düzeltmeleri',
                    'story_points' => 2,
                    'assignee' => 'Ali Özkan',
                    'priority' => 'high',
                    'type' => 'bug'
                ]
            ]
        ];

        $teamMembers = [
            (object)['name' => 'Ahmet Yılmaz', 'role' => 'Frontend Developer', 'capacity' => 40],
            (object)['name' => 'Ayşe Kaya', 'role' => 'Backend Developer', 'capacity' => 40],
            (object)['name' => 'Fatma Şen', 'role' => 'Full Stack Developer', 'capacity' => 35],
            (object)['name' => 'Emre Yılmaz', 'role' => 'DevOps Engineer', 'capacity' => 30],
            (object)['name' => 'Zeynep Arslan', 'role' => 'QA Engineer', 'capacity' => 40],
            (object)['name' => 'Ali Özkan', 'role' => 'Scrum Master', 'capacity' => 20]
        ];

        $sprintStats = [
            'total_points' => 42,
            'completed_points' => 7,
            'remaining_points' => 35,
            'days_remaining' => 8,
            'velocity' => 28
        ];

        return view('scrum.board', compact('sprints', 'stories', 'teamMembers', 'sprintStats'));
    }
}