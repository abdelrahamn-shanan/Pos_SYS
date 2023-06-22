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
        Schema::create('admin_treasuries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Admin_id')->unsigned()->index();
            $table->bigInteger('treasury_id')->unsigned()->index();
            $table->tinyInteger('active');
            $table->integer('com_code');
            $table->integer('added_by');
            $table->integer('updated_by')->nullable();
            $table->date('date')->nullable();

            $table->foreign('Admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
            
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
        Schema::dropIfExists('admin_treasuries');
    }
};
