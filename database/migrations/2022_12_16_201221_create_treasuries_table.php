<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasuries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('is_master')->default(0);
            $table->integer('last_recieve_exchange');
            $table->integer('last_recieve_collect');
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->integer('com_code');
            $table->date('date');
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
        Schema::dropIfExists('treasuries');
    }
};
