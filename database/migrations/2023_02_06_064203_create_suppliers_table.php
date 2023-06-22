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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('account_number')->index();
            $table->integer('Supplier_code');
            $table->bigInteger('Supplier_Category_id')->unsigned()->index();
            $table->tinyInteger('start_balance_status');
            $table->decimal('start_balance');
            $table->decimal('current_balance');
            $table->string('notes');
            $table->integer('com_code');
            $table->tinyInteger('is_archieved');
            $table->integer('added_by');
            $table->integer('updated_by');;
            $table->date('date');
            $table->timestamps();
            $table->foreign('account_number')->references('account_number')->on('accounts')->onDelete('cascade');
            $table->foreign('Supplier_Category_id')->references('id')->on('supplierscategories')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};