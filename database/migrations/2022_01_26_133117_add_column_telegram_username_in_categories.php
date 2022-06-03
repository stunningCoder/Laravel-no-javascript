<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTelegramUsernameInCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	
		    
        if (Schema::hasTable('users')) {
	
	        if (!Schema::hasColumn('users','telegram_username')) {
	        	
		        Schema::table('users', function (Blueprint $table) {
		        	
			        $table->string('telegram_username', 255)->nullable()->after('username');
			        
		        });
		        
	        }
	
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
