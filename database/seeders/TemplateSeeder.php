<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        \App\Models\Template::insert([
            ['name'=>'Sample 1','description'=>'Desc 1','price'=>5,'thumbnail_url'=>null,'is_active'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Sample 2','description'=>'Desc 2','price'=>10,'thumbnail_url'=>null,'is_active'=>true,'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
    
}
\App\Models\Template::create([

    'description' => 'Test Template Description',
    'price' => 100,
    'is_active' => true,
]);