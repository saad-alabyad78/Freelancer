<?php

use App\Models\Client;
use App\Models\Freelancer;
use App\Models\ClientOffer;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Freelancer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ClientOffer::class)->constrained()->cascadeOnDelete();
            $table->date('finished_at')->nullable();
            $table->integer('price');
            $table->integer('client_money');
            $table->integer('days');
            $table->boolean('client_ok')->nullable();
            $table->boolean('freelancer_ok')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
