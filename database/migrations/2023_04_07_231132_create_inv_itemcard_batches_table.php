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
        Schema::create('inv_itemcard_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('store_id'); // كود المخزن
            $table->integer('item_code'); // كود المنتج الالي
            $table->integer('inv_uoms_id'); // كود الوحدة الالي
            $table->decimal('unit_cost_price'); //سعر شراء الوحدة
            $table->decimal('total_cost_price'); //   اجمالى سعر الشراء
            $table->date('production_date'); //   تاريخ الانتاج
            $table->date('expired_date'); //   تاريخ الانتهاء
            $table->integer('com_code'); //   
            $table->tinyInteger('is_send_to_archived'); 
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();   
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
        Schema::dropIfExists('inv_itemcard_batches');
    }
};