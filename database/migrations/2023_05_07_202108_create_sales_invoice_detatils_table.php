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
        Schema::create('sales_invoice_detatils', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->tinyInteger('item_card_type'); // نوع المنتج 1 مخزني 0 استهلاكي
            $table->bigInteger('Sales_order_auto_serial');   //  رقم الفاتوره التسلسلي
            $table->decimal('quantity',10,4);
            $table->decimal('uom_id');
            $table->tinyInteger('uom_type'); // نوع الوحده  1رئيسية او0 تجزئة
            $table->decimal('unit_price',10,2);
            $table->decimal('total_price',10,2);
            $table->date('invoice_date');   //    تاريخ الفاتوره
            $table->integer('com_code');
            $table->bigInteger('item_code'); // كود الصنف
            $table->bigInteger('batch_id'); //   رقم الباتش بالمخزن التى تم خروج الصنف منها
            $table->date('production_date'); //   تاريخ الانتاج
            $table->date('expired_date'); //   تاريخ الانتهاء
            $table->string('notes');
            $table->integer('added_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('sales_invoice_detatils');
    }
};