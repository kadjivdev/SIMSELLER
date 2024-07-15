<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliseursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliseurs', function (Blueprint $table) {
            $table->id();
            $table->string('matricule');
            $table->string('civilite');
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone')->unique();;
            $table->string('email')->nullable()->unique();
            $table->string('photo')->nullable();
            $table->foreignId('type_avaliseur_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('avaliseurs');
    }
}
