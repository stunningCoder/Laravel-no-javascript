<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartdbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartdb', function (Blueprint $table) {
	        $table->uuid('id');
	        $table->primary('id');
	
	        $table->integer('quantity');
	        $table -> string('coin_name', 5) ->default('btc');
	        $table -> string('type', 10) ->default('normal'); //digital
	
	        $table -> uuid('offer_id')-> nullable();
	        $table -> uuid('buyer_id') -> nullable(); // buyer == null when user deletes account
	        $table -> uuid('vendor_id')-> nullable();
	        $table -> uuid('shipping_id')-> nullable();
	
	        $table -> timestamps();
	
	        $table -> foreign('offer_id') -> references('id') -> on('offers') -> onDelete('cascade');
	        $table -> foreign('buyer_id') -> references('id') -> on('users') -> onDelete('set null');
	        $table -> foreign('vendor_id') -> references('id') -> on('vendors') -> onDelete('cascade');
	        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cartdb');
    }
}
