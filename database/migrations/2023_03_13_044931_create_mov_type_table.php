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
        Schema::create('mov_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyinteger('active',1)->default(1);
            $table->tinyInteger('in_screen',1); // 1 Exchange 2 collect
            $table->tinyInteger('is_private_internal',1)->default(0);
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
        Schema::dropIfExists('mov_types');
    }
};