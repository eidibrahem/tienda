<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // مهم

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) أنشئ/حدّث المستخدم التجريبي بدون تكرار
        User::updateOrCreate(
            ['email' => 'test@example.com'], // شرط عدم التكرار
            [
                'name' => 'Test User',
                'password' => Hash::make('password'), // حط باسوورد، حتى لو مش هتستعمله
                'email_verified_at' => now(),
            ]
        );

        // 2) شغّل بقية السييدر
        $this->call([
            TemplateSeeder::class,
        ]);
    }
}
