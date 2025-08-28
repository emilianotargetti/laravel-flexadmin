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

        // Styles directive
        Blade::directive('styles', function ($area) {
            $output = '';
            $area = trim($area, "'");

            // Aggiungi i file CSS
            foreach (FlexAsset::getCss($area) as $css) {
                $output .= '<link rel="stylesheet" href="' . $css . '">' . PHP_EOL;
            }

            // Aggiungi gli stili inline
            $inlineCss = trim(join(PHP_EOL, FlexAsset::getInlineCss($area)));

            if (!empty($inlineCss)) {
                $output .= PHP_EOL . '<style>' . PHP_EOL . $inlineCss . PHP_EOL . '</style>' . PHP_EOL;
            }

            // Stampa gli errori se presenti
            $errors = FlexAsset::getErrors('css');
            if (!empty($errors)) {
                $output .= '<!-- File not found: ' . join(PHP_EOL, $errors) . ' -->' . PHP_EOL;
            }

            // Restituisce il markup corretto
            return $output;
        });

        // Scripts directive
        Blade::directive('scripts', function ($area) {
            $area = trim($area, "'");
            $output = '';

            // Aggiungi i file JavaScript
            foreach (FlexAsset::getJs($area) as $js) {
                $output .= '<script type="text/javascript" src="' . $js . '"></script>' . PHP_EOL;
            }

            // Aggiungi gli script inline
            $inlineJs = trim(join(PHP_EOL, FlexAsset::getInlineJs($area)));
            if (!empty($inlineJs)) {
                $output .= PHP_EOL . '<script type="text/javascript">' . PHP_EOL . $inlineJs . PHP_EOL . '</script>' . PHP_EOL;
            }

            // Stampa gli errori se presenti
            $errors = FlexAsset::getErrors('js');
            if (!empty($errors)) {
                $output .= '<!-- File not found: ' . join(PHP_EOL, $errors) . ' -->' . PHP_EOL;
            }

            // Restituisce il markup corretto
            return $output;
        });

    }
}
