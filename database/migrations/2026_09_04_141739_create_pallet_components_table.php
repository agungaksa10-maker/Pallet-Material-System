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
        Schema::create('pallet_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pallet_sticker_id')->constrained('pallet_stickers')->cascadeOnDelete();
            $table->string('component_name')->index();
            $table->string('quantity')->nullable();
            $table->string('batch_no')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pallet_components');
    }
};
