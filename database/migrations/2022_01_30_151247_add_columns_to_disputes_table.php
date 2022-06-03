<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDisputesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disputes', function (Blueprint $table) {
        	
	        $table -> enum('state', array_keys(\App\Dispute::$states)) ->default(\App\Dispute::DEFAULT_STATE);
	        $table -> uuid('disputed_by_user_id')-> nullable();
	        $table -> uuid('escalated_by_user_id')->nullable();
	        $table -> uuid('appeal_by_user_id')->nullable();
	        $table -> uuid('resolved_by_user_id')->nullable();
	        $table->unsignedTinyInteger('appeals_count', false)->default(0);
	
	        $table -> foreign('disputed_by_user_id') -> references('id') -> on('users') -> onDelete('set null');
	        $table -> foreign('escalated_by_user_id') -> references('id') -> on('users') -> onDelete('set null');
	        $table -> foreign('appeal_by_user_id') -> references('id') -> on('users') -> onDelete('set null');
	        $table -> foreign('resolved_by_user_id') -> references('id') -> on('users') -> onDelete('set null');
	        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disputes', function (Blueprint $table) {
           //
        });
    }
}
