<?php

// File: emilianotargetti//flexasset/src/Providers/FlexAssetServiceProvider.php

namespace EmilianoTargetti\FlexAsset\Providers;

use EmilianoTargetti\FlexAsset\Helpers\FlexAssetManager;
use EmilianoTargetti\FlexAsset\Facades\FlexAsset;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class FlexAssetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('flexasset', function ($app) {
            return new FlexAssetManager();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $this->publishes([
            __DIR__.'/../../config/flexasset.php' => config_path('flexasset.php'),
        ], 'flexasset-config');

        Blade::directive('styles', function(){
            $output = '';
            foreach(FlexAsset::getCss() as $css){
                $output .= '<link rel="stylesheet" href="'. $css .'">' . PHP_EOL;
            }

            return $output ;
        });

        Blade::directive('scripts', function(){
            $output = '';
            foreach(FlexAsset::getJs() as $js){
                $output .= '<script src="'. $js .'"></script>' . PHP_EOL;
            }

            return $output ;
        });

    }
}
