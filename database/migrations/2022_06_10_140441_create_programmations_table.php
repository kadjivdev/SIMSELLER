<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programmations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->date('dateprogrammer');
            $table->date('dateSortie')->nullable();
            $table->float('qteprogrammer');
            $table->date('datelivrer')->nullable();
            $table->float('qtelivrer')->nullable();
            $table->string('bl')->nullable();
            $table->string('document')->nullable();
            $table->longText('observation')->nullable();
            $table->string('statut');
            $table->foreignId('detail_bon_commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('camion_id')->constrained()->onDelete('cascade');
            $table->foreignId('chauffeur_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programmations');
    }
}
