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
            $table->string('name');

            $table->string('profile_image_url' , 2048)->nullable();
            $table->string('background_image_url' , 2048)->nullable();

            $table->string('profile_image_id')->nullable();
            $table->string('background_image_id')->nullable();

            $table->string('username')->unique();
            $table->text('description')->nullable();
            $table->string('size');
            
            $table->date('verified_at')->nullable();
            $table->string('city');
            $table->string('region');
            $table->string('street_address');

            $table->string('industry_name');

            $table->foreign('industry_name')
                ->references('name')
                ->on('industries')
                ->onDelete('restrict')
                ->onUpdate('restrict');
                
            $table->timestamps();
            
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
