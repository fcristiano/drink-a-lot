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
        Schema::create('orders', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('bar_table_id', false, true);
            $table->bigInteger('bartender_id', false, true)->nullable();
            $table->bigInteger('waiter_id', false, true)->nullable();
            $table->tinyInteger('status', false, true);
            $table->float('amount', 8, 2, true)->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('bar_table_id')->references('id')->on('bar_tables');
            $table->foreign('bartender_id')->references('id')->on('bartenders');
            $table->foreign('waiter_id')->references('id')->on('waiters');
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
