<?php


use App\Models\Freelancer;
use App\Models\SubCategory;
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
        Schema::create('freelancer_offers', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Freelancer::class)->constrained()->restrictOnDelete() ;
            $table->foreignIdFor(SubCategory::class)->constrained()->restrictOnDelete() ;

            $table->string('title') ;
            $table->string('status')->index() ;
            $table->string('description' , 2000) ;

            $table->integer('min_price') ;
            $table->integer('max_price') ;
            $table->integer('days') ;

            $table->integer('proposals_count')->default(0)->nullable() ;

            $table->timestamp('posted_at')->nullable() ;
             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_offers');
    }
};
