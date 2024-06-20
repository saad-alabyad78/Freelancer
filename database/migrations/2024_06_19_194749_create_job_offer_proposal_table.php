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
        Schema::create('job_offer_proposal', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(JobOffer::class)->constrained()->cascadeOnDelete() ;
            $table->foreignIdFor(Freelancer::class)->constrained()->cascadeOnDelete() ;
            $table->string('message') ;
            $table->date('rejected_at')->nullable() ;
            $table->date('accepted_at')->nullable() ;
            $table->timestamps() ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offer_proposal');
    }
};
