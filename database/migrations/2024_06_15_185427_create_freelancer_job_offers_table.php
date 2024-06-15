<?php

use App\Models\JobOffer;
use App\Models\Freelancer;
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
        Schema::create('freelancer_job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Freelancer::class)->constrained() ;
            $table->foreignIdFor(JobOffer::class)->constrained() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_job_offers');
    }
};
