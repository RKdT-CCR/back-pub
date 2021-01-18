<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('nome');
            $table->string('cpf')->comment('cpf');
            $table->date('birth')->comment('data de nascimento');
            $table->string('educational_level');
            $table->string('occupation_area')->comment('area de atuacao');
            $table->string('number')->nullable()->comment('telefone');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('persons', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
        Schema::drop('persons');
    }
}
