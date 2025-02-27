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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('subtotal', 10,2);
            $table->double('shipping', 10,2);
            $table->string('coupon_code')->nullable;
            $table->double('discount', 10,2)->nullable;
            $table->double('grand_total', 10,2);

            // user addresse
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->text('address');
            $table->string('appartment')->nullable;
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('mobile');
            $table->text('notes')->nullable;
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
