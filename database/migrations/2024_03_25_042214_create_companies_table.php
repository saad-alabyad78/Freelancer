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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('profile_image')->nullable();
            $table->string('background_image')->nullable();

            $table->string('username')->unique();
            $table->text('description')->nullable();
            $table->string('size');
            $table->string('name');
            $table->date('verified_at')->nullable();
            $table->string('city');
            $table->string('region');
            $table->string('street_address');
            $table->timestamps();

            $table->index(['username']);

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
