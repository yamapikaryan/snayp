<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->bigInteger('player_id')->unsigned();
            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onDelete('cascade');

            $table->bigInteger('etp_id')->unsigned();
            $table->foreign('etp_id')
                ->references('id')->on('etps')
                ->onDelete('cascade');

            $table->bigInteger('auction_status_id')->unsigned();
            $table->foreign('auction_status_id')
                ->references('id')->on('auction_statuses')
                ->onDelete('cascade');

            $table->string('auction_link');
            $table->integer('auction_number');
            $table->boolean('is_price_request');
            $table->boolean('is_223fz');

            $table->bigInteger('client_id')->unsigned();
            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade');

            $table->dateTime('application_deadline');
            $table->dateTime('auction_datetime');
            $table->integer('maxprice');
            $table->integer('ourprice');
            $table->integer('finalprice');
            $table->text('auction_winner');
            $table->text('comment');
            $table->boolean('is_archived');
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
        Schema::dropIfExists('auctions');
    }
}
