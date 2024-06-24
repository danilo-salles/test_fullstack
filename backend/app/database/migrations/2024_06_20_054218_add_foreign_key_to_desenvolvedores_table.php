<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('desenvolvedores', function (Blueprint $table) {
            // Adiciona a chave estrangeira
            $table->foreign('nivel_id')
                ->references('id')
                ->on('niveis')
                ->onDelete('cascade'); // Define a ação de deleção em cascata
        });
    }

    public function down()
    {
        Schema::table('desenvolvedores', function (Blueprint $table) {
            // Remove a chave estrangeira, caso seja necessário fazer rollback
            $table->dropForeign(['nivel_id']);
        });
    }
};
