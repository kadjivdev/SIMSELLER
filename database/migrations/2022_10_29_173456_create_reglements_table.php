<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReglementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->string('code',45)->nullable()->unique();
            $table->string('reference')->nullable();
            $table->date('date');
            $table->float('montant');
            $table->string('document')->nullable();
            $table->foreignId('vente_id')->constrained('ventes')->onDelete('cascade');
            $table->foreignId('type_detail_recu_id')->constrained('type_detail_recus')->onDelete('cascade');
            $table->foreignId('compte_id')->nullable()->constrained('comptes')->onDelete('cascade');
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('reglements');
    }
}
