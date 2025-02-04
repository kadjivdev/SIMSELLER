<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCamionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camions', function (Blueprint $table) {
            $table->id();
            $table->string('marque');
            $table->string('immatriculationTracteur')->unique();
            $table->string('immatriculationRemorque')->unique();
            $table->integer('nombreIssieu');
            $table->float('tonnage')->nullable();
            $table->string('statut');
            $table->string('photo')->nullable();
            $table->bigInteger('chauffeur_id')->nullable();
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
        Schema::dropIfExists('camions');
    }
}
