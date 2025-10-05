<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Sample 1',
                'description' => 'Custom video with personalized message',
                'price' => 5.00,
                'thumbnail_url' => null,
                'video_url' => '/videos/sample1.mp4',
                'is_active' => true,
            ],
            [
                'name' => 'Sample 2',
                'description' => 'Custom video with photos and music',
                'price' => 10.00,
                'thumbnail_url' => null,
                'video_url' => '/videos/sample2.mp4',
                'is_active' => true,
            ],
            [
                'name' => 'Sample 3',
                'description' => 'Professional custom video production',
                'price' => 15.00,
                'thumbnail_url' => null,
                'video_url' => '/videos/sample3.mp4',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $data) {
            // 👇 لو القالب بنفس الاسم مش موجود، أضِفه
            Template::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        $this->command->info('✅ TemplateSeeder: تم إدخال القوالب بنجاح بدون تكرار.');
    }
}
