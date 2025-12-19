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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('apartment_id')->constrained('apartments')->cascadeOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->enum('booking_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->float('rating')->nullable();
            $table->timestamps();
            $table->index(['apartment_id', 'check_in_date', 'check_out_date'], 'bookings_apart_dates_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
