<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemcards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('item_type'); // نوع المنتج 1 مخزني 0 استهلاكي
            $table->integer('itemcard_category'); // فئة الصنف
            $table->integer('parent_itemcard_id'); // كود الصنف الاب 
            $table->integer('does_has_retailunit'); // هل له وحدة تجزئة
            $table->integer('retail_uom'); //وحدة قياس التجزئة
            $table->integer('uom_id'); // وحدة التجزئة الاب 
            $table->decimal('retail_uom_quantityToParent'); //نسبة وحدة التجزئة الى الوحدة الاب
            $table->integer('item_code'); // تسلسل الصنف
            $table->string('barcode')->nullable(); // باركود الصنف 
            $table->boolean('active');
            $table->integer('added_by');
            $table->integer('updated_by');
            $table->integer('com_code');
            $table->date('date');
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
        Schema::dropIfExists('itemcards');
    }
};