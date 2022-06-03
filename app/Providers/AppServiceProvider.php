<?php

namespace App\Providers;

use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\SlackHandler;

//for db logs
//use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
/*//	    if(env('APP_DEBUG')) {
		    \Illuminate\Support\Facades\DB::listen(function($query) {
			    \Illuminate\Support\Facades\File::append(
				    storage_path('/logs/'.date('Y-m-d_H-s-i_').'query.log'),
				    $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
			    );
		    });
//	    }*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
