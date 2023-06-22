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
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('account_type')->unsigned()->nullable();;
            $table->bigInteger('parent_account_number');
            $table->bigInteger('account_number');
            $table->decimal('start_balance');
            $table->decimal('current_balance');
            $table->string('notes');
            $table->bigInteger('other_table_fk');
            $table->tinyInteger('active');
            $table->integer('com_code');
            $table->tinyInteger('is_archieved');
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('account_type')->references('id')->on('accounts_types')->onDelete('cascade');

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};