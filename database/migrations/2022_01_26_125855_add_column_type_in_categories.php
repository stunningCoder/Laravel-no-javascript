<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypeInCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (Schema::hasTable('categories')) {
	    	
		    Schema::table('categories', function (Blueprint $table) {
		    
			   $table->boolean('is_physical')->default(1)->after('name');
			   
		    });
		    
	    }
 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    if (Schema::hasColumn('categories', 'is_physical')) {
	    	
		    Schema::table('categories', function (Blueprint $table) {
		    	
			    $table->dropColumn('is_physical');
			    
		    });
		    
	    }
    }
}
