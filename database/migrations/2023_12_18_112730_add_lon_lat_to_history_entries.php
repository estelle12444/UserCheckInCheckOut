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
        Schema::table('history_entries', function (Blueprint $table) {
            $table->double('lat');
            $table->double('lon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_entries', function (Blueprint $table) {
            $table->double('lat');
            $table->double('lon');
        });
    }
};
