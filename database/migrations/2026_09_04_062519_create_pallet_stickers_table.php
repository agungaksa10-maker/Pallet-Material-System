<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pallet_stickers', function (Blueprint $table) {
            $table->id();
            $table->string('site'); // OKI II, IKPD, IKPP, TELL, ISC
            $table->string('category'); // Dressing, Consumable
            $table->unsignedSmallInteger('pallet_number'); // 1 to 500
            $table->string('pallet_code')->nullable()->index(); // e.g. PLT-OKI2-DRS-001
            $table->string('material_name')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('quantity')->nullable();
            $table->text('notes')->nullable();
            $table->string('user_id')->nullable()->index();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();

            $table->index(['site', 'category', 'pallet_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pallet_stickers');
    }
};
