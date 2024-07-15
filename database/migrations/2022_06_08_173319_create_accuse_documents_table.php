<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccuseDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accuse_documents', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('libelle');
            $table->date('date');
            $table->float('montant');
            $table->string('reference')->nullable();
            $table->string('document')->nullable();
            $table->longText('observation')->nullable();
            $table->foreignId('bon_commande_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_document_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('accuse_documents');
    }
}
