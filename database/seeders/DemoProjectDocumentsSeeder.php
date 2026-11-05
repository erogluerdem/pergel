<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\User;
use Carbon\Carbon;

class DemoProjectDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $p1 = Project::create([
                'name' => 'Vadi Panorama Evleri & Ticaret Merkezi',
                'code' => 'PRJ-2025-01',
                'description' => 'Karma yaşam projesi, 240 konut ve 30 ticari alan.',
                'status' => 'active',
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => Carbon::now()->addMonths(18),
            ]);

            $p2 = Project::create([
                'name' => 'Gökçen Kule Plaza & Rezidans',
                'code' => 'PRJ-2025-02',
                'description' => 'A+ Ofis kulesi ve akıllı iş merkezi inşaatı.',
                'status' => 'active',
                'start_date' => Carbon::now()->subMonths(3),
                'end_date' => Carbon::now()->addMonths(24),
            ]);

            $p3 = Project::create([
                'name' => 'Sahil Marina Villaları Projesi',
                'code' => 'PRJ-2025-03',
                'description' => 'Lüks lobi ve özel marinalı müstakil villa kompleksi.',
                'status' => 'planning',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(30),
            ]);

            $projects = Project::all();
        }

        $adminUser = User::first();
        $disciplines = ['Mimari', 'Statik', 'Mekanik', 'Elektrik', 'Peyzaj', 'Ruhsat & İdari', 'Sözleşmeler'];
        $statuses = ['approved', 'review', 'approved', 'draft', 'approved'];
        $mimePDF = 'application/pdf';

        foreach ($projects as $index => $project) {
            // Folders for project
            $f1 = DocumentFolder::firstOrCreate([
                'project_id' => $project->id,
                'name' => '01_Mimari ve Uygulama Projeleri',
                'slug' => '01-mimari-ve-uygulama',
            ]);

            $f2 = DocumentFolder::firstOrCreate([
                'project_id' => $project->id,
                'name' => '02_Statik ve Betonarme Çizimleri',
                'slug' => '02-statik-betonarme',
            ]);

            $f3 = DocumentFolder::firstOrCreate([
                'project_id' => $project->id,
                'name' => '03_MEP (Mekanik-Elektrik-Tesisat)',
                'slug' => '03-mep-tesisat',
            ]);

            $f4 = DocumentFolder::firstOrCreate([
                'project_id' => $project->id,
                'name' => '04_Resmi Ruhsat & İmar Belgeleri',
                'slug' => '04-ruhsat-imar',
            ]);

            $demoDocs = [
                [
                    'folder_id' => $f1->id,
                    'name' => $project->code . ' - Vaziyet Planı & Mimari Kesitler.pdf',
                    'code' => $project->code . '-ARC-001',
                    'file_path' => 'documents/demo_arc_001.pdf',
                    'mime_type' => $mimePDF,
                    'version' => 'V2.1',
                    'status' => 'approved',
                    'discipline' => 'Mimari',
                    'uploaded_by' => $adminUser?->id,
                    'uploaded_at' => Carbon::now()->subDays(rand(2, 20)),
                ],
                [
                    'folder_id' => $f1->id,
                    'name' => $project->code . ' - Kat Planları & Odalama Şemaları.pdf',
                    'code' => $project->code . '-ARC-002',
                    'file_path' => 'documents/demo_arc_002.pdf',
                    'mime_type' => $mimePDF,
                    'version' => 'V1.0',
                    'status' => 'approved',
                    'discipline' => 'Mimari',
                    'uploaded_by' => $adminUser?->id,
                    'uploaded_at' => Carbon::now()->subDays(rand(5, 30)),
                ],
                [
                    'folder_id' => $f2->id,
                    'name' => $project->code . ' - Temel ve Bodrum Betonarme Kalıp Planı.pdf',
                    'code' => $project->code . '-STR-010',
                    'file_path' => 'documents/demo_str_010.pdf',
                    'mime_type' => $mimePDF,
                    'version' => 'V3.0',
                    'status' => 'approved',
                    'discipline' => 'Statik',
                    'uploaded_by' => $adminUser?->id,
                    'uploaded_at' => Carbon::now()->subDays(rand(10, 45)),
                ],
                [
                    'folder_id' => $f3->id,
                    'name' => $project->code . ' - Yangın Söndürme & Havalandırma Projesi.pdf',
                    'code' => $project->code . '-MEP-105',
                    'file_path' => 'documents/demo_mep_105.pdf',
                    'mime_type' => $mimePDF,
                    'version' => 'V1.2',
                    'status' => 'review',
                    'discipline' => 'Mekanik',
                    'uploaded_by' => $adminUser?->id,
                    'uploaded_at' => Carbon::now()->subDays(rand(1, 15)),
                ],
                [
                    'folder_id' => $f4->id,
                    'name' => $project->code . ' - Belediye Yapı Ruhsatı ve Onaylı Pafta.pdf',
                    'code' => $project->code . '-OFF-901',
                    'file_path' => 'documents/demo_off_901.pdf',
                    'mime_type' => $mimePDF,
                    'version' => 'V1.0',
                    'status' => 'approved',
                    'discipline' => 'Ruhsat & İdari',
                    'uploaded_by' => $adminUser?->id,
                    'uploaded_at' => Carbon::now()->subDays(rand(15, 60)),
                ],
            ];

            foreach ($demoDocs as $docData) {
                Document::create(array_merge($docData, [
                    'project_id' => $project->id,
                ]));
            }
        }
    }
}
