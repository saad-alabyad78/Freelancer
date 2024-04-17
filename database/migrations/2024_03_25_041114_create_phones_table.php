<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade');

            $table->string('phone_number')->nullable() ;

            $table->dateTime('phone_number_verified_at')->nullable();
            $table->string('phone_number_otp_code')->nullable();
            $table->dateTime('phone_number_otp_expired_date')->nullable();

            $table->unique(['user_id']) ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phone');
    }
};
