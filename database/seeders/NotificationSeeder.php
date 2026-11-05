<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        if ($users->count() == 0) return;
        
        $firstUser = $users->first();
        $secondUser = $users->skip(1)->first() ?? $firstUser;
        
        $notifications = [
            [
                'type' => 'task_assigned',
                'title' => 'Yeni Görev Atandı',
                'message' => 'Size "Website Yenileme Projesi" görevi atandı.',
                'data' => ['task_id' => 1, 'due_date' => now()->addDays(7)->format('Y-m-d')],
                'user_id' => $firstUser->id,
                'priority' => 'high',
                'is_actionable' => true,
                'action_url' => '/tasks/1',
                'created_by' => $secondUser->id,
            ],
            [
                'type' => 'low_stock',
                'title' => 'Düşük Stok Uyarısı',
                'message' => 'Güvenlik Kaskı ürününde stok azalıyor. Mevcut: 15 adet',
                'data' => ['inventory_item_id' => 4, 'current_stock' => 15, 'min_stock' => 10],
                'user_id' => $firstUser->id,
                'priority' => 'urgent',
                'is_actionable' => true,
                'action_url' => '/inventory/4',
                'created_by' => null,
            ],
            [
                'type' => 'system_update',
                'title' => 'Sistem Güncellemesi',
                'message' => 'Sistem başarıyla güncellendi. Yeni özellikler kullanıma hazır.',
                'data' => ['version' => '2.1.0', 'features' => ['notification_system', 'role_management']],
                'user_id' => $firstUser->id,
                'priority' => 'medium',
                'is_actionable' => false,
                'created_by' => null,
            ],
            [
                'type' => 'task_completed',
                'title' => 'Görev Tamamlandı',
                'message' => 'Güvenlik Güncellemeleri görevi başarıyla tamamlandı.',
                'data' => ['task_id' => 3, 'completed_by' => $firstUser->id],
                'user_id' => $secondUser->id,
                'priority' => 'low',
                'is_actionable' => true,
                'is_read' => true,
                'read_at' => now()->subHours(2),
                'action_url' => '/tasks/3',
                'created_by' => $firstUser->id,
            ],
            [
                'type' => 'deadline_approaching',
                'title' => 'Yaklaşan Deadline',
                'message' => 'CRM Entegrasyonu görevinin bitiş tarihi yaklaşıyor (3 gün kaldı).',
                'data' => ['task_id' => 2, 'days_remaining' => 3],
                'user_id' => $firstUser->id,
                'priority' => 'urgent',
                'is_actionable' => true,
                'action_url' => '/tasks/2',
                'created_by' => null,
            ],
            [
                'type' => 'user_mention',
                'title' => 'Yorumda Bahsedildiniz',
                'message' => 'Ahmet Yılmaz sizi "Veritabanı Optimizasyonu" görevinde bahsetti.',
                'data' => ['task_id' => 4, 'mentioned_by' => $secondUser->id],
                'user_id' => $firstUser->id,
                'priority' => 'medium',
                'is_actionable' => true,
                'action_url' => '/tasks/4#comments',
                'created_by' => $secondUser->id,
            ],
            [
                'type' => 'inventory_added',
                'title' => 'Yeni Ürün Eklendi',
                'message' => 'Envantere "Elektrik Kablosu 2.5mm" ürünü eklendi.',
                'data' => ['inventory_item_id' => 5, 'quantity' => 15],
                'user_id' => $secondUser->id,
                'priority' => 'low',
                'is_actionable' => false,
                'created_by' => $firstUser->id,
            ],
            [
                'type' => 'backup_completed',
                'title' => 'Yedekleme Tamamlandı',
                'message' => 'Günlük sistem yedeği başarıyla tamamlandı.',
                'data' => ['backup_size' => '2.3GB', 'backup_time' => '00:05:32'],
                'user_id' => $firstUser->id,
                'priority' => 'low',
                'is_actionable' => false,
                'is_read' => true,
                'read_at' => now()->subHours(6),
                'created_by' => null,
            ],
        ];
        
        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}
