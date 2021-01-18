<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('descrição');
            $table->string('alternative_1')->nullable()->comment('alternativa a');
            $table->string('alternative_2')->nullable()->comment('alternativa b');
            $table->string('alternative_3')->nullable()->comment('alternativa c');
            $table->string('alternative_4')->nullable()->comment('alternativa d');
            $table->string('alternative_5')->nullable()->comment('alternativa e');
            $table->string('correct_answer')->nullable()->comment('resposta correta');
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
        Schema::dropIfExists('tests');
    }
}
