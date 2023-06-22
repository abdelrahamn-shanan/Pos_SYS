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
        Schema::create('admins_shifts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Admin_id')->unsigned()->index();
            $table->bigInteger('treasury_id')->unsigned()->index();
            $table->decimal('treasuries_balnce_in_shift_start',10); // رصيد الخزنة في بدايه استلام الشفت للمستخدم
            $table->date('start_date'); // توقيت بداية الشفت
            $table->date('end_date'); // تاريخ انتهاء الشفت
            $table->tinyinteger('is_finished'); // هل تم انتهاء الشفت
            $table->tinyinteger('is_delivered_and_review'); // هل تم مرراجعه واستلام شفت الخزنة
            $table->integer('delivered_to_admin_id'); //كود المستخدم الذي تسلم هذا الشفت واراجعه'
            $table->integer('delivered_to_admin_sift_id'); //كود الشفت الذي تسلم هذا الشفت وارجعه
            $table->integer('delivered_to_treasuries_id'); // كود الخزنه التي راجعت واستلمت هذا الشفت
            $table->decimal('money_should_delivered'); // النقدية التي يفترض ان تسلم
            $table->decimal('what_really_delivered'); // المبلغ الذي تم استلامه
            $table->tinyinteger('money_state'); // صفر متزن - واحد  يوجد عجز - اثنين يوجد زيادة'
            $table->decimal('money_state_value'); //قيمة العجز او الزياده ان وجدت'
            $table->tinyinteger('receive_type'); // واحد استلام علي نفس الخزنة - اثنين استلام علي خزنة اخري'
            $table->date('review_receive_date'); // تاريخ مراجعة واستلام الشفت
            $table->integer('treasuries_transactions_id'); // رقم الايصال بجدول تحصيل النقدية لحركة الخزن
            $table->string('notes');
            $table->tinyInteger('active');
            $table->integer('com_code');
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->date('date');
            $table->timestamps();

            $table->foreign('Admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins_shifts');
    }
};
