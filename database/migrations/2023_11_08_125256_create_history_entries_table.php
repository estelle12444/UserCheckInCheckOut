<?php

use App\Models\Employee;
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
        Schema::create('history_entries', function (Blueprint $table) {
            $table->id();
            $table->double('in_confidence');
            $table->time('time_at_in');
            $table->date('day_at_in');
            $table->double('out_confidence')->nullable();
            $table->time('time_at_out')->nullable();
            $table->date('day_at_out')->nullable();
            $table->integer('localisation_id');
            $table->foreignIdFor(Employee::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_entries');
    }
};
