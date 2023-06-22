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
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('bill_type'); // فاتوره اجل او كاش 
            $table->tinyInteger('store_id'); // المخزن المخرج للفاتورة
            $table->bigInteger('auto_serial');   //  رقم الفاتوره التسلسلي
            $table->date('sales_invoice_data');
            $table->bigInteger('customer_code'); 
            $table->bigInteger('account_number');  
            $table->bigInteger('delegate_code');  // كود المندوب 
            $table->bigInteger('delegate_auto_invoice_number');  // رقم الالي  للمندوب 
            $table->bigInteger('sales_materials_types_id');  //فئات الاصناف
            $table->tinyInteger('is_approved'); // هل الفاتوره تم اعتمادها ؟
            $table->decimal('total_cost_items',10); //اجمالي الاصناف فقط
            $table->decimal('total_before_discount',10); //اجمالى الفاتوره قبل الخصم
            $table->tinyInteger('discount_type')->nullable(); // نوع الخصم 1 خصم نسبة   2 خصم قيمة او مبلغ
            $table->decimal('discount_percent',10); // نسبة الخصم
            $table->decimal('discount_value',10); // قيمة الخصم
            $table->decimal('tax_percent',10)->nullable(); // نسبة الضريبة
            $table->decimal('tax_value',10); // قيمة الضريبة
            $table->decimal('total_cost',10); // اجمالى الفاتوره
            $table->decimal('money_for_account',10);
            $table->decimal('what_paid',10); //في حالة إذا كانت الفاتوره اجل 
            $table->decimal('what_remain',10); // المتبقي من قيمة الفاتوره
            $table->bigInteger('treasuries_transaction_id')->nullable();
            $table->decimal('customer_balance_before',10)->nullable(); // رصيد العميل قبل
            $table->decimal('customer_balance_after',10)->nullable(); // رصيد العميل بعد الفاتوره
            $table->integer('com_code');
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
        Schema::dropIfExists('sales');
    }
};