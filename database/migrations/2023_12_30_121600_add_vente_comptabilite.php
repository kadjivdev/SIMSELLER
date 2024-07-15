<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVenteComptabilite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->string('ctl_payeur')->nullable();
            $table->date('date_comptabilisation')->nullable();
            $table->integer('taux_aib')->nullable();
            $table->integer('taux_tva')->nullable();
            $table->integer('prix_TTC')->nullable();
            $table->integer('prix_Usine')->nullable();
            $table->integer('marge')->nullable();
            $table->date('date_traitement')->nullable();
            $table->foreignId('user_traitement')->constrained('users')->nullable();
            $table->date('date_envoie_commercial')->nullable();
            $table->foreignId('user_envoie_commercial')->constrained('users')->nullable();

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
