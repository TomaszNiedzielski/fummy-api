<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('offer_id');
            $table->string('purchaser_name');
            $table->string('purchaser_email');
            $table->string('for_whom')->nullable();
            $table->string('occasion');
            $table->text('instructions');
            $table->boolean('is_private');
            $table->boolean('is_paid')->default(false);
            $table->string('purchase_key');
            $table->string('session_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('deadline')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
