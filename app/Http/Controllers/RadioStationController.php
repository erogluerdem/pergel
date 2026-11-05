<?php

namespace App\Http\Controllers;

use App\Models\UserRadioStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RadioStationController extends Controller
{
    /**
     * Fetch all available radio stations (System + User Custom)
     */
    public function index()
    {
        // Auto-seed system default stations if table is empty
        if (UserRadioStation::count() === 0) {
            $this->seedDefaultStations();
        }

        $userId = Auth::id();

        $stations = UserRadioStation::where(function ($query) use ($userId) {
            $query->where('is_system', true);
            if ($userId) {
                $query->orWhere('user_id', $userId);
            }
        })
        ->orderBy('is_system', 'desc')
        ->orderBy('order', 'asc')
        ->orderBy('id', 'asc')
        ->get();

        return response()->json([
            'success' => true,
            'stations' => $stations,
        ]);
    }

    /**
     * Store a new custom radio station in SQL database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'src' => 'required|string|max:1000',
            'genre' => 'nullable|string|max:100',
            'icon' => 'nullable|string|max:50',
        ]);

        $station = UserRadioStation::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'genre' => $validated['genre'] ?? 'Özel Kullanıcı Radyosu',
            'src' => $validated['src'],
            'icon' => $validated['icon'] ?? 'ri-radio-2-line',
            'is_stream' => true,
            'is_system' => false,
            'order' => 99,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Radyo kanalı SQL veritabanına başarıyla kaydedildi.',
            'station' => $station,
        ]);
    }

    /**
     * Delete a custom radio station
     */
    public function destroy($id)
    {
        $station = UserRadioStation::findOrFail($id);

        if ($station->is_system) {
            return response()->json([
                'success' => false,
                'message' => 'Sistem varsayılan radyoları silinemez.',
            ], 403);
        }

        if ($station->user_id && $station->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu kanalı silme yetkiniz yok.',
            ], 403);
        }

        $station->delete();

        return response()->json([
            'success' => true,
            'message' => 'Radyo kanalı veritabanından silindi.',
        ]);
    }

    /**
     * Initial System Stations Seeder
     */
    private function seedDefaultStations()
    {
        $defaults = [
            [
                'title' => '📻 Power FM',
                'genre' => 'Canlı Yabancı Pop & Hit Müzik',
                'src' => 'https://listen.powerapp.com.tr/powerfm/mpeg/icecast.audio',
                'icon' => 'ri-radio-line',
                'is_stream' => true,
                'is_system' => true,
                'order' => 1,
            ],
            [
                'title' => '📻 PowerTürk FM',
                'genre' => 'Canlı Türkçe Pop & En İyiler',
                'src' => 'https://listen.powerapp.com.tr/powerturk/mpeg/icecast.audio',
                'icon' => 'ri-radio-line',
                'is_stream' => true,
                'is_system' => true,
                'order' => 2,
            ],
            [
                'title' => '📻 Radyo Fenomen',
                'genre' => 'Canlı Pop & Dance Hits',
                'src' => 'https://listen.powerapp.com.tr/radyofenomen/mpeg/icecast.audio',
                'icon' => 'ri-radio-line',
                'is_stream' => true,
                'is_system' => true,
                'order' => 3,
            ],
            [
                'title' => '🎧 Deep Focus Lofi',
                'genre' => 'Konsantrasyon & Kodlama Beats',
                'src' => 'https://cdn.pixabay.com/download/audio/2022/05/27/audio_1808fbf07a.mp3',
                'icon' => 'ri-headphone-line',
                'is_stream' => false,
                'is_system' => true,
                'order' => 4,
            ],
            [
                'title' => '🌧️ Yağmur & Fırtına',
                'genre' => 'Sakinleştirici Doğal Yağmur',
                'src' => 'https://cdn.pixabay.com/download/audio/2022/03/10/audio_c8c8a73467.mp3',
                'icon' => 'ri-rainy-line',
                'is_stream' => false,
                'is_system' => true,
                'order' => 5,
            ],
            [
                'title' => '☕ Kafe Ambiyansı',
                'genre' => 'Sıcak Kahve Dükkanı Sesi',
                'src' => 'https://cdn.pixabay.com/download/audio/2021/09/06/audio_8b21c439ed.mp3',
                'icon' => 'ri-cup-line',
                'is_stream' => false,
                'is_system' => true,
                'order' => 6,
            ],
            [
                'title' => '🎹 Klasik Piyano',
                'genre' => 'Mozart, Chopin & Bach',
                'src' => 'https://cdn.pixabay.com/download/audio/2022/01/18/audio_d0a13f69d2.mp3',
                'icon' => 'ri-music-2-line',
                'is_stream' => false,
                'is_system' => true,
                'order' => 7,
            ],
            [
                'title' => '🎷 Smooth Jazz',
                'genre' => 'Dinlendirici Çalışma Cazı',
                'src' => 'https://cdn.pixabay.com/download/audio/2022/03/15/audio_c8b9d3b134.mp3',
                'icon' => 'ri-music-fill',
                'is_stream' => false,
                'is_system' => true,
                'order' => 8,
            ],
        ];

        foreach ($defaults as $d) {
            UserRadioStation::create($d);
        }
    }
}
