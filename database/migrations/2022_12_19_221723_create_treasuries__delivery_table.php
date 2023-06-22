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
        Schema::create('treasuries__delivery', function (Blueprint $table) {
            $table->id();
            $table->integer('Traesuries_id'); // الخزنة اللتى سوف تستلم
            $table->integer('treasuries_can_delivery_id'); // الخزنة التى سيتم تسليمها
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->integer('com_code');
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
        Schema::dropIfExists('treasuries__delivery');
    }
};
