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
        Schema::create('inv_itemcard_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('inv_itemcard_movements_categories');
            $table->bigInteger('item_code');
            $table->integer('items_movements_types');
            $table->bigInteger('FK_table');
            $table->bigInteger('FK_table_details');
            $table->string('byan');
            $table->string('quantity_befor_movement');
            $table->string('quantity_after_movement');
            $table->integer('added_by');
            $table->date('date');
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
        Schema::dropIfExists('inv_itemcard_movements');
    }
};