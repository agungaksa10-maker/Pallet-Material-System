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
        Schema::table('pallet_components', function (Blueprint $table) {
            $table->string('kolom', 10)->nullable()->after('batch_no');
            $table->string('tingkat', 10)->nullable()->after('kolom');
        });

        Schema::table('pallet_stickers', function (Blueprint $table) {
            $table->string('kolom', 10)->nullable()->after('notes');
            $table->string('tingkat', 10)->nullable()->after('kolom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pallet_components', function (Blueprint $table) {
            $table->dropColumn(['kolom', 'tingkat']);
        });

        Schema::table('pallet_stickers', function (Blueprint $table) {
            $table->dropColumn(['kolom', 'tingkat']);
        });
    }
};
