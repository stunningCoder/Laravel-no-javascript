<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAppeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appeals', function (Blueprint $table) {
	        $table->uuid('id');
	        $table->primary('id');
	        
	        $table -> uuid('purchase_id')->nullable(); //relation to get dispute

	        
	        $table -> uuid('appealer_id')->nullable();
	        $table -> uuid('resolver_id')->nullable();
	        
            $table->timestamps();
	
	        $table->foreign('appealer_id')->references('id')->on('users') -> onDelete('set null');
	        $table->foreign('resolver_id')->references('id')->on('users') -> onDelete('set null');
	        
	        $table->foreign('purchase_id')->references('id')->on('purchases') -> onDelete('set null');
	        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appeals');
    }
}
