<?php

namespace Database\Seeders;

use App\Models\File;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    public function run(): void
    {
        // ملفات مرتبطة بمشاريع مختلفة
        File::create([
            'title' => 'Project Plan',
            'url' => 'https://example.com/files/project_plan.pdf',
            'public_id' => 'file_public_id_001',
            'size' => 2048,
            'extension' => 'pdf',
            'filable_id' => 1,
            'filable_type' => 'App\Models\Project',
            'deleted' => false,
        ]);

        File::create([
            'title' => 'Design Mockup',
            'url' => 'https://example.com/files/design_mockup.png',
            'public_id' => 'file_public_id_002',
            'size' => 5120,
            'extension' => 'png',
            'filable_id' => 2,
            'filable_type' => 'App\Models\Portfolio',
            'deleted' => false,
        ]);

        File::create([
            'title' => 'Contract Agreement',
            'url' => 'https://example.com/files/contract_agreement.docx',
            'public_id' => 'file_public_id_003',
            'size' => 1024,
            'extension' => 'docx',
            'filable_id' => 3,
            'filable_type' => 'App\Models\Project',
            'deleted' => false,
        ]);

        File::create([
            'title' => 'Marketing Strategy',
            'url' => 'https://example.com/files/marketing_strategy.pdf',
            'public_id' => 'file_public_id_004',
            'size' => 3072,
            'extension' => 'pdf',
            'filable_id' => 4,
            'filable_type' => 'App\Models\Campaign',
            'deleted' => false,
        ]);

        File::create([
            'title' => 'Client Presentation',
            'url' => 'https://example.com/files/client_presentation.pptx',
            'public_id' => 'file_public_id_005',
            'size' => 4096,
            'extension' => 'pptx',
            'filable_id' => 5,
            'filable_type' => 'App\Models\Portfolio',
            'deleted' => false,
        ]);
    }
}
