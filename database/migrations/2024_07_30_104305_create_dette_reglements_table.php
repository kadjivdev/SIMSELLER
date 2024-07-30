<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetteReglementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dette_reglements', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->date('date');
            $table->float('montant');
            $table->text('document')->nullable();
            $table->foreignId('client')
                ->nullable()
                ->constrained('ventes')
                ->onUpdate("CASCADE")
                ->onDelete('CASCADE');
            $table->foreignId('type_detail_recu')
                ->nullable()
                ->constrained('type_detail_recus', "id")
                ->onUpdate("CASCADE")
                ->onDelete('CASCADE');
            $table->foreignId('compte')
                ->nullable()
                ->constrained('comptes', "id")
                ->onUpdate("CASCADE")
                ->onDelete('CASCADE');
            $table->foreignId('operator')
                ->nullable()
                ->constrained('users', "id")
                ->onUpdate("CASCADE")
                ->onDelete('CASCADE');
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
        Schema::dropIfExists('dette_reglements');
    }
}
