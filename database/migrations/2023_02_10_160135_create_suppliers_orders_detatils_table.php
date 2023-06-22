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
        Schema::create('suppliers_orders_detatils', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->tinyInteger('order_type')->default(1);// 1 purchases  2 return 3 general return 
            $table->bigInteger('Supplier_order_auto_serial')->unsigned()->index();   //  رقم الفاتوره التسلسلي
            $table->decimal('delivered_quantity',10);
            $table->decimal('uom_id');
            $table->tinyInteger('uom_type'); // نوع الوحده  1رئيسية او0 تجزئة
            $table->decimal('unit_price',10);
            $table->decimal('total_price',10);
            $table->date('order_date');   //    تاريخ الفاتوره
            $table->integer('com_code');
            $table->bigInteger('item_code')->unsigned()->index(); // تسلسل الصنف
            $table->bigInteger('batch_id'); // رقم الباتش بالمخزن
            $table->string('notes');
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps(); 
            $table->foreign('item_code')->references('item_code')->on('itemcards')->onDelete('cascade');
            $table->foreign('Supplier_order_auto_serial')->references('auto_serial')->on('suppliers_with_orders')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers_orders_detatils');
    }
};
