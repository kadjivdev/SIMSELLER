<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{


    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('civilite')->nullable();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('photo')->nullable();
            $table->string('sigle')->nullable();
            $table->string('raisonSociale')->nullable();
            $table->string('logo')->nullable();
            $table->string('telephone')->unique();
            $table->string('email')->unique();
            $table->string('adresse')->nullable();
            $table->string('domaine')->nullable();
            $table->boolean('statutCredit')->default(0);
            $table->boolean('sommeil')->default(0);
            $table->foreignId('type_client_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('clients');
    }
}
