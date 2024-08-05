<?php

use App\Models\Image;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id() ;
            $table->integer('price');
            $table->string('name') ;
            $table->text('description');
            $table->foreignIdFor(Image::class)->constrained()->cascadeOnDelete() ;
            $table->foreignIdFor(Freelancer::class)->constrained()->cascadeOnDelete() ;
            $table->softDeletes() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
