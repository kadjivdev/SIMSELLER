<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recus', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->nullable()->unique();
            $table->string('libelle');
            $table->date('date');
            $table->string('reference')->nullable();
            $table->float('tonnage');
            $table->float('montant');
            $table->string('document')->nullable();
            $table->foreignId('bon_commande_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('recus');
    }
}
