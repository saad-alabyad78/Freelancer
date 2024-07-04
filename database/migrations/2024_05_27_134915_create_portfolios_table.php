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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_id')->constrained();
            $table->string('url')->nullable();

            $table->string('title');
            $table->string('section');

            $table->string('description');
            $table->date('date')->nullable();

            $table->integer('views_count')->default(0) ;
            $table->integer('likes_count')->default(0) ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
