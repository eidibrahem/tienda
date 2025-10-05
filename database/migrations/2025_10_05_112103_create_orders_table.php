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
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('template_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->string('email');
            $t->string('country')->nullable();
            $t->text('description')->nullable();
            $t->json('photos')->nullable();
            $t->decimal('price', 10, 2)->default(0);
            $t->string('status')->default('created'); // created|paid|processing|delivered|canceled
            $t->json('payment_data')->nullable();     // أي داتا دفع خام
            $t->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
