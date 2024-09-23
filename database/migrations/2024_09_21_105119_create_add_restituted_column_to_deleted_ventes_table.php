<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddRestitutedColumnToDeletedVentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deleted_ventes', function (Blueprint $table) {
            $table->boolean("restituted")->default(false);
            $table->foreignId("restitutor")->nullable()->constrained("users","id")->onUpdate("CASCADE")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deleted_ventes', function (Blueprint $table) {
            //
        });
    }
}
