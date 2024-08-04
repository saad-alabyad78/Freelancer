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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();

            $table->integer('proposals_count')->default(0) ;
            
            $table->string('description');
            
            $table->string('status')->index();
            $table->string('location_type')->index();
            $table->string('attendance_type')->index();

            $table->integer('max_salary')->nullable() ;
            $table->integer('min_salary')->nullable() ;

            $table->boolean('transportation')->default(false);
            $table->boolean('health_insurance')->default(false);
            $table->boolean('military_service')->default(false);

            $table->boolean('military_service_required')->default(false);

            $table->tinyInteger('max_age')->nullable() ;
            $table->tinyInteger('min_age')->nullable() ;

            $table->boolean('age_required')->default(false);

            $table->string('gender')->nullable();
            $table->boolean('gender_required')->default(false);

            $table->foreignId('job_role_id')->constrained();
            $table->foreignId('company_id')->constrained();

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
        Schema::dropIfExists('job_offers');
    }
};
