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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();

            $table->string('profile_image_url' , 2000)->nullable();
            $table->string('background_image_url' , 2000)->nullable();
            $table->string('profile_image_id')->nullable();
            $table->string('background_image_id')->nullable();

            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('city');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
