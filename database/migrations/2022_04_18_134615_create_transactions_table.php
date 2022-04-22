<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_address_id')->nullable()->constraint('customer_addresses', 'id');
            $table->integer('code_transaction');
            $table->date('transaction_date');
            $table->foreignId('product_id')->nullable()->constraint('products', 'id');
            $table->foreignId('payment_method_id')->nullable()->constraint('payment_methods', 'id');


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
        Schema::dropIfExists('transactions');
    }
}
