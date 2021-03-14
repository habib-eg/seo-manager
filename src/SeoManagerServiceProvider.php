<?php

namespace Lionix\SeoManager;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Lionix\SeoManager\Commands\GenerateSeoManagerData;
use Lionix\SeoManager\Commands\GenerateSitemap;
use Lionix\SeoManager\Components\Input;
use Lionix\SeoManager\Components\SeoModelComponent;
use Lionix\SeoManager\Routes\SeoRouter;

class SeoManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $path = __DIR__;

        $this->publishes([
            $path . '/config/seo-manager.php' => config_path('seo-manager.php'),
        ], 'config');

        $this->publishes([
            $path . '/assets' => public_path('vendor/lionix'),
        ], 'assets');

        $this->publishes([
            $path . '/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            $path . '/views' => resource_path('views/vendor/seo-manger'),
        ], 'views');

        $this->loadRoutesFrom($path . '/Routes/seo-manager.php');
        $this->loadMigrationsFrom($path . '/migrations');
        $this->loadViewsFrom($path . '/views', 'seo-manager');


        $this->commands([
            GenerateSeoManagerData::class,
            GenerateSitemap::class
        ]);
        // Blade Directives
        $this->registerBladeDirectives();
        Blade::component('seo-model', SeoModelComponent::class,'seo');
        Blade::component('input', Input::class,'seo');
    }

    /**
     * Register Blade Directives
     */
    public function registerBladeDirectives()
    {

        Blade::directive('meta', function ($expression) {
            $meta = '';
            $expression = trim($expression, '\"\'');
            $metaData = metaData($expression);
            if (is_array($metaData)) {
                foreach ($metaData as $key => $og) {
                    $meta .= "<meta property='{$key}' content='{$og}'/>";
                }
            } else {
                $meta .= "<meta property='{$expression}' content='{$metaData}'/>";
            }
            return $meta;
        });
        Blade::directive('keywords', function () {
            return "<meta property='keywords' content='" . metaKeywords() . "'/>";
        });
        Blade::directive('url', function () {
            return "<meta property='url' content='" . metaUrl() . "'/>";
        });
        Blade::directive('author', function () {
            return "<meta property='author' content='" . metaAuthor() . "'/>";
        });
        Blade::directive('description', function () {
            return "<meta property='description' content='" . metaDescription() . "'/>";
        });
        Blade::directive('title', function () {
            return "<meta property='title' content='" . metaTitle() . "'/>";
        });
        Blade::directive('openGraph', function ($expression) {
            $expression = trim($expression, '\"\'');
            $meta = '';
            $metaOpenGraph = metaOpenGraph($expression);
            if (is_array($metaOpenGraph)) {
                foreach ($metaOpenGraph as $key => $og) {
                    $meta .= "<meta property='{$key}' content='{$og}'/>";
                }
            } else {
                $meta .= "<meta property='{$expression}' content='{$metaOpenGraph}'/>";
            }
            return $meta;
        });
        Blade::directive('titleDynamic', function () {
            return metaTitleDynamic();
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Router::mixin(new SeoRouter());
        $this->mergeConfigFrom(
            __DIR__ . '/config/seo-manager.php', 'seo-manager'
        );
        $this->app->bind('seomanager', function () {
            return new SeoManager();
        });
        $this->app->alias('seomanager', SeoManager::class);

    }

}
