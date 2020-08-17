<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_securities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('auction_id')->unsigned()->nullable();
            $table->foreign('auction_id')->references('id')->on('auctions');

            $table->integer('application_security_price');
            $table->boolean('application_security_is_cash');
            $table->boolean('application_security_is_paid');
            $table->integer('contract_security_price');
            $table->boolean('contract_security_is_cash');
            $table->boolean('contract_security_is_paid');
            $table->integer('warranty_security_price');
            $table->boolean('warranty_security_is_cash');
            $table->boolean('warranty_security_is_paid');

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
        Schema::dropIfExists('auction_securities');
    }
}
