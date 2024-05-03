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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('industry_name') ;

            $table->foreign('industry_name')
                ->references('name')
                ->on('industries')
                ->onDelete('restrict')
                ->onUpdate('restrict') ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if(Schema::hasColumn('companies' ,'industry_name')){
                $table->dropColumn('industry_name');
            }
        });
    }
};
