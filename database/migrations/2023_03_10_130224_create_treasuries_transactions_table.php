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
        Schema::create('treasuries_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('treasury_id')->unsigned()->index();
            $table->bigInteger('admins_shifts_id')->unsigned()->index();
            $table->integer('mov_type');
            $table->bigInteger('fk')->nullable(); // كود حركة الشفت
            $table->bigInteger('account_number')->index(); // رقم الحساب المالى
            $table->tinyInteger('is_account'); // هل هذا رقم حساب
            $table->tinyInteger('is_approved');// هل تم اعتماد الحركة
            $table->decimal('money');// قيمة المبلغ بالخزنة
            $table->decimal('money_for_account');// قيمة المبلغ المستحق للحساب او على الحساب
            $table->string('byan',225);
            $table->integer('com_code');
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->date('date');
            $table->foreign('account_number')->references('account_number')->on('accounts')->onDelete('cascade');
            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
            $table->foreign('admins_shifts_id')->references('id')->on('admins_shifts')->onDelete('cascade');
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
        Schema::dropIfExists('treasuries_transactions');
    }
};