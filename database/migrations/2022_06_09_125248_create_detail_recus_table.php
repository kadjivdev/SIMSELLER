<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailRecusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_recus', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('reference')->nullable();
            $table->date('date');
            $table->float('montant');
            $table->string('document')->nullable();
            $table->foreignId('recu_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_detail_recu_id')->constrained()->onDelete('cascade');
            $table->foreignId('compte_id')->nullable()->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('detail_recus');
    }
}
