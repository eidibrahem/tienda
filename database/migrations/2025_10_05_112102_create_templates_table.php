<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('templates', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->text('description')->nullable();
            $t->decimal('price', 10, 2)->default(0);
            $t->string('thumbnail_url')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
