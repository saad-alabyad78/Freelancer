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
        Schema::create('skillables', function (Blueprint $table) {
            $table->foreignId('skill_id')->constrained()->onDelete('cascade') ;

            $table->unsignedBigInteger('skillable_id');
            $table->string('skillable_type');

            $table->boolean('required')->default(false) ;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skillables');
    }
};
