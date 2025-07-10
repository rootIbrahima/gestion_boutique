<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->decimal('montant_total', 10, 2);
            $table->string('mode_paiement'); // espèces, carte, mobile...
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ventes');
    }
};
