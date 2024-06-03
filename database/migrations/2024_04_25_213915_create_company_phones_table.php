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
        Schema::create('company_phones', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->date('verified_at')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_phones');
    }
};
