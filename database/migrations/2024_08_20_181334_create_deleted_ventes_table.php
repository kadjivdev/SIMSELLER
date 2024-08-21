<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedVentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_ventes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('date');
            $table->string('montant')->nullable();
            $table->string('statut');
            $table->foreignId('commande_client_id')->constrained()->onDelete('cascade');
            $table->string('users');

            $table->string('pu')->nullable();
            $table->string('qteTotal')->nullable();
            $table->string('remise')->nullable();
            $table->foreignId('produit_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('type_vente_id')->nullable()->constrained()->onDelete('cascade');

            $table->text('vente_validation')->nullable();
            $table->string('transport')->nullable();
            $table->text('destination')->nullable();
            $table->string('ctl_payeur')->nullable();
            $table->date('date_comptabilisation')->nullable();
            $table->date('date_traitement')->nullable();
            $table->string('user_traitement')->nullable();
            $table->date('date_envoie_commercial')->nullable();
            $table->string('user_envoie_commercial')->nullable();
            $table->boolean('comptabiliser')->default(false);

            $table->longText('comptabiliser_history')->nullable();
            $table->boolean('annuler')->default(false);
            $table->longText('annuler_history')->nullable();

            $table->boolean('valide')->default(false);
            $table->date('validated_date')->nullable();
            $table->text('traited_date')->nullable();










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
        Schema::dropIfExists('deleted_ventes');
    }
}
