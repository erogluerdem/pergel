<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class UserSettingsController extends Controller
{
    public function setPageLock(Request $request)
    {
        $data = $request->validate([
            'locked' => 'required|boolean',
            'password' => 'nullable|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Rate limit: check locked_until
        $now = now();
        if (!empty($user->page_lock_locked_until) && $user->page_lock_locked_until > $now) {
            return response()->json(['message' => 'Çok fazla başarısız deneme. Bir süre sonra tekrar deneyin.'], 423);
        }

        // Require password
        if (empty($data['password'])) {
            return response()->json(['message' => 'Parola gerekli'], 422);
        }

        if (!Hash::check($data['password'], $user->password)) {
            // increment failed attempts
            $user->page_lock_failed_attempts = ($user->page_lock_failed_attempts ?? 0) + 1;
            if ($user->page_lock_failed_attempts >= 5) {
                $user->page_lock_locked_until = $now->copy()->addMinutes(5);
                $user->page_lock_failed_attempts = 0; // reset counter after lock
            }
            $user->save();
            return response()->json(['message' => 'Parola hatalı'], 403);
        }

        // success: reset counters
        $user->page_lock_failed_attempts = 0;
        $user->page_lock_locked_until = null;

        $user->page_locked = (bool) $data['locked'];
        $user->save();

        return response()->json(['locked' => $user->page_locked]);
    }

    /**
     * Display user profile settings.
     */
    public function settings()
    {
        $user = Auth::user();
        
        // Mock user data for demonstration
        $userData = [
            'id' => $user->id ?? 1,
            'name' => $user->name ?? 'Kullanıcı Adı',
            'email' => $user->email ?? 'user@example.com',
            'phone' => $user->phone ?? '+90 555 123 45 67',
            'department' => $user->department ?? 'Yazılım Geliştirme',
            'position' => $user->position ?? 'Senior Developer',
            'avatar' => $user->avatar ?? null,
            'bio' => $user->bio ?? 'Kısa biyografi metni buraya gelecek.',
            'location' => $user->location ?? 'İstanbul, Türkiye',
            'timezone' => $user->timezone ?? 'Europe/Istanbul',
            'language' => $user->language ?? 'tr',
            'date_format' => $user->date_format ?? 'd.m.Y',
            'notifications' => [
                'email_notifications' => $user->email_notifications ?? true,
                'push_notifications' => $user->push_notifications ?? true,
                'task_reminders' => $user->task_reminders ?? true,
                'project_updates' => $user->project_updates ?? true,
                'system_alerts' => $user->system_alerts ?? false
            ],
            'privacy' => [
                'profile_visibility' => $user->profile_visibility ?? 'team',
                'show_email' => $user->show_email ?? false,
                'show_phone' => $user->show_phone ?? true,
                'show_status' => $user->show_status ?? true
            ],
            'security' => [
                'two_factor_enabled' => $user->two_factor_enabled ?? false,
                'login_notifications' => $user->login_notifications ?? true,
                'session_timeout' => $user->session_timeout ?? 120
            ]
        ];

        // Notification settings mock data
        $notification_settings = [
            'email' => [
                'task_reminders' => [
                    'title' => 'Görev Hatırlatıcıları',
                    'description' => 'Yaklaşan görev son tarihleri için e-posta bildirimleri',
                    'enabled' => $user->email_notifications ?? true
                ],
                'system_updates' => [
                    'title' => 'Sistem Güncellemeleri',
                    'description' => 'Sistem bakımları ve güncellemeler hakkında bildirimler',
                    'enabled' => $user->system_alerts ?? false
                ],
                'project_notifications' => [
                    'title' => 'Proje Bildirimleri',
                    'description' => 'Proje durumu değişiklikleri ve güncellemeler',
                    'enabled' => $user->project_updates ?? true
                ],
                'security_alerts' => [
                    'title' => 'Güvenlik Uyarıları',
                    'description' => 'Güvenlik açıkları ve şüpheli aktiviteler',
                    'enabled' => $user->login_notifications ?? true
                ]
            ],
            'push' => [
                'instant_messages' => [
                    'title' => 'Anlık Mesajlar',
                    'description' => 'Takım üyelerinden gelen anlık mesajlar',
                    'enabled' => $user->push_notifications ?? true
                ],
                'urgent_tasks' => [
                    'title' => 'Acil Görevler',
                    'description' => 'Yüksek öncelikli görev bildirimleri',
                    'enabled' => $user->task_reminders ?? true
                ],
                'meeting_reminders' => [
                    'title' => 'Toplantı Hatırlatması',
                    'description' => 'Yaklaşan toplantılar için anında bildirim',
                    'enabled' => true
                ]
            ]
        ];

        // Integrations mock data
        $integrations = [
            [
                'id' => 'slack',
                'name' => 'Slack',
                'description' => 'Takım iletişimi ve bildirimler',
                'icon' => 'https://via.placeholder.com/40/4A154B/FFFFFF?text=S',
                'connected' => false,
                'status' => 'Bağlı değil'
            ],
            [
                'id' => 'google_calendar',
                'name' => 'Google Calendar',
                'description' => 'Toplantı ve etkinlik senkronizasyonu',
                'icon' => 'https://via.placeholder.com/40/4285F4/FFFFFF?text=G',
                'connected' => true,
                'status' => 'Bağlı'
            ],
            [
                'id' => 'github',
                'name' => 'GitHub',
                'description' => 'Kod depo yönetimi ve proje takibi',
                'icon' => 'https://via.placeholder.com/40/181717/FFFFFF?text=GH',
                'connected' => true,
                'status' => 'Bağlı'
            ],
            [
                'id' => 'dropbox',
                'name' => 'Dropbox',
                'description' => 'Dosya depolama ve paylaşım',
                'icon' => 'https://via.placeholder.com/40/0061FF/FFFFFF?text=D',
                'connected' => false,
                'status' => 'Bağlı değil'
            ],
            [
                'id' => 'trello',
                'name' => 'Trello',
                'description' => 'Proje yönetimi ve görev takibi',
                'icon' => 'https://via.placeholder.com/40/0079BF/FFFFFF?text=T',
                'connected' => true,
                'status' => 'Bağlı'
            ]
        ];

        // Billing mock data
        $billing = [
            'current_plan' => [
                'name' => 'Pro Plan',
                'description' => 'Gelişmiş özellikler ve öncelikli destek',
                'price' => '99',
                'features' => [
                    'Sınırsız görev',
                    '10 GB depolama',
                    'Öncelikli destek',
                    'Gelişmiş raporlama'
                ]
            ],
            'next_billing_date' => '01.11.2024',
            'invoices' => [
                [
                    'id' => 'INV-2024-001',
                    'date' => '01.09.2024',
                    'amount' => '₺99,00',
                    'status' => 'paid',
                    'description' => 'Pro Plan - Eylül 2024',
                    'download_url' => '#'
                ],
                [
                    'id' => 'INV-2024-002',
                    'date' => '01.08.2024',
                    'amount' => '₺99,00',
                    'status' => 'paid',
                    'description' => 'Pro Plan - Ağustos 2024',
                    'download_url' => '#'
                ],
                [
                    'id' => 'INV-2024-003',
                    'date' => '01.07.2024',
                    'amount' => '₺99,00',
                    'status' => 'paid',
                    'description' => 'Pro Plan - Temmuz 2024',
                    'download_url' => '#'
                ]
            ],
            'payment_method' => [
                'type' => 'credit_card',
                'last_four' => '4242',
                'expiry' => '12/26',
                'brand' => 'Visa'
            ]
        ];

        return view('profile.settings', compact('userData', 'notification_settings', 'integrations', 'billing'));
    }

    /**
     * Update user profile settings.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|in:tr,en',
            'date_format' => 'nullable|string|in:d.m.Y,Y-m-d,m/d/Y',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'task_reminders' => 'boolean',
            'project_updates' => 'boolean',
            'system_alerts' => 'boolean',
            'profile_visibility' => 'nullable|string|in:public,team,private',
            'show_email' => 'boolean',
            'show_phone' => 'boolean',
            'show_status' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'login_notifications' => 'boolean',
            'session_timeout' => 'nullable|integer|min:30|max:480'
        ]);

        $updatable = [];
        foreach ($validated as $key => $val) {
            if (Schema::hasColumn('users', $key)) {
                $updatable[$key] = $val;
            }
        }
        if (isset($validated['position']) && !isset($updatable['position']) && Schema::hasColumn('users', 'title')) {
            $updatable['title'] = $validated['position'];
        }
        $user->update($updatable);

        if (Route::has('settings.index')) {
            return redirect()->route('settings.index')
                ->with('success', 'Profil ayarları başarıyla güncellendi.');
        }

        return redirect()->back()
            ->with('success', 'Profil ayarları başarıyla güncellendi.');
    }
}
