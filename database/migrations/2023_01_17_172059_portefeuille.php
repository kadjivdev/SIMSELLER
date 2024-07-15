<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Portefeuille extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('portefeuilles', function (Blueprint $table){

            $table->foreignId('agent_id')->constrained('agents')->nullable();
            $table->foreignId('client_id')->constrained('clients')->nullable();
            $table->date('datedebut')->nullable();
            $table->date('datefin')->nullable();
            $table->boolean('statut')->nullable();
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
        //
    }
}
