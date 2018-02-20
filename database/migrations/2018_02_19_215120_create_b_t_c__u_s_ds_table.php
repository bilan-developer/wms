<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBTCUSDsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('btc_usd', function (Blueprint $table) {
            $table->increments('id');
            $table->float('buy_price', 16, 8);
            $table->float('sell_price', 16, 8);
            $table->float('last_trade', 16, 8);
            $table->float('high', 16, 8);
            $table->float('low', 16, 8);
            $table->float('avg', 16, 8);
            $table->float('vol', 16, 8);
            $table->float('vol_curr', 16, 8);
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
        Schema::dropIfExists('b_t_c__u_s_ds');
    }
}
