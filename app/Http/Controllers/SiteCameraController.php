<?php

namespace App\Http\Controllers;

use App\Models\SiteCamera;
use App\Models\Project;
use Illuminate\Http\Request;

class SiteCameraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = SiteCamera::orderBy('created_at', 'desc')->paginate(12);
        $projects = Project::pluck('name', 'id');

        $stats = [
            'total_cameras' => SiteCamera::count(),
            'online_count' => SiteCamera::where('status', 'online')->count(),
            'offline_count' => SiteCamera::where('status', 'offline')->count(),
            'maintenance_count' => SiteCamera::where('status', 'bakimda')->count(),
        ];

        return view('site_cameras.index', compact('items', 'projects', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'camera_name' => 'required|string',
            'location' => 'required|string',
            'stream_url' => 'required|string',
            'status' => 'required|in:online,offline,bakimda',
        ]);

        SiteCamera::create($validated);

        return back()->with('success', 'IP Şantiye CCTV kamerası başarıyla eklendi.');
    }

    public function update(Request $request, SiteCamera $siteCamera)
    {
        $validated = $request->validate([
            'status' => 'required|in:online,offline,bakimda',
        ]);

        $siteCamera->update($validated);

        return back()->with('success', 'Kamera yayın durumu güncellendi.');
    }

    public function destroy(SiteCamera $siteCamera)
    {
        $siteCamera->delete();
        return back()->with('success', 'IP kamera kaydı silindi.');
    }
}
