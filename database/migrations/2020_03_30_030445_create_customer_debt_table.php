<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDebtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_debt', function (Blueprint $table) {
            $table->string('invoice_no');
            $table->integer('customer_id');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->bigInteger('total');
            $table->date('payment_date')->nullable();
            $table->bigInteger('payment');
            $table->bigInteger('over');           
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_debt');
    }
}
