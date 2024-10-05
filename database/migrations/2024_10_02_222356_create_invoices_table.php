<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('total');
            $table->string('discount_percentage')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('delivery_charge')->nullable();
            $table->string('grand_total');
            $table->string('payable_amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('due')->nullable();
            $table->string('payment_status')->default('Unpaid');
            $table->string('customer_name')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onDelete('set null');
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
        Schema::dropIfExists('invoices');
    }
}
