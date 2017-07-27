<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('employee_id')->unsigned()->nullable(true);
            $table->integer('item_type')->unsigned()->nullable(false);
            $table->string('shoulder')->nullable(true);
            $table->string('arm_hole')->nullable(true);
            $table->string('chest_1')->nullable(true);
            $table->string('chest_2')->nullable(true);
            $table->string('waist')->nullable(true);
            $table->string('hip')->nullable(true);
            $table->string('slit')->nullable(true);
            $table->string('top_length')->nullable(true);
            $table->string('f_neck')->nullable(true);
            $table->string('b_neck')->nullable(true);
            $table->string('sleeve_length')->nullable(true);
            $table->string('sleeve_breadth')->nullable(true);
            $table->string('sleeve_type')->nullable(true);
            $table->string('hip_size')->nullable(true);
            $table->string('ankle')->nullable(true);
            $table->string('bottom_length')->nullable(true);
            $table->string('knee')->nullable(true);
            $table->string('bottom_breadth')->nullable(true);
            $table->string('thigh')->nullable(true);
            $table->string('description');
            $table->integer('amount')->unsigned()->nullable(false);
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
        Schema::dropIfExists('items');
    }
}
