<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenteDeleteDemandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vente_delete_demands', function (Blueprint $table) {
            $table->id();
            $table->foreignId("vente")
                ->nullable()
                ->constrained("ventes", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->foreignId("demandeur")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->text("raison")->nullable();
            $table->text("prouve_file")->nullable();

            $table->boolean("valide")->default(false);
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
        Schema::dropIfExists('vente_delete_demands');
    }
}
