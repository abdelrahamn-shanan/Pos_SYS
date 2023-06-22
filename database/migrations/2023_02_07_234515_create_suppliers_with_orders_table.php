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
        Schema::create('suppliers_with_orders', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->tinyInteger('order_type');// 1 purchases  2 return 3 general return 
            $table->tinyInteger('bill_type'); // فاتوره اجل او كاش 
            $table->bigInteger('auto_serial');   //  رقم الفاتوره التسلسلي
            $table->bigInteger('Doc_no');   //  رقم الفاتوره التسلسلي
            $table->date('order_date');   //    تاريخ الفاتوره
            $table->integer('Supplier_code');   //  رقم الفاتوره التسلسلي
            $table->bigInteger('account_number');
            $table->tinyInteger('is_approved'); // هل الفاتوره تم اعتمادها ؟
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
            $table->decimal('supplier_balance_before',10)->nullable(); // رصيد المورد قبل
            $table->decimal('supplier_balance_after',10)->nullable(); // رصيد المورد بعد الفاتوره
            $table->integer('com_code');
            $table->string('notes');
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->foreign('Supplier_code')->references('Supplier_code')->on('suppliers ')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers_with_orders');
    }
};
