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
        Schema::create('client_offer_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Freelancer::class)->constrained()->cascadeOnDelete() ;
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnDelete() ;
            $table->foreignIdFor(ClientOffer::class)->constrained()->cascadeOnDelete() ;
            $table->string('message') ;
            $table->date('accepted_at')->nullable() ;
            $table->date('rejected_at')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_offer_proposals');
    }
};
