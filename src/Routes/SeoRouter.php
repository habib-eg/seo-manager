<?php

namespace Lionix\SeoManager\Routes;


use Closure;
use Illuminate\Support\Facades\Route;
use Lionix\SeoManager\Controllers\{
    ImportController, LocalesController, ManagerController
};
use Lionix\SeoManager\Middleware\ClearViewCache;
use Lionix\SeoManager\Middleware\SeoManager;

class SeoRouter
{
    /**
     * @return Closure
     */
    public function seoManger()
    {
        return function (array $options = []) {
            $options['middleware'] = array_merge($options['middleware']??[],array_merge(config('seo-manager.middleware', []), [SeoManager::class, ClearViewCache::class]));
            $options['prefix'] = $options['prefix'] ?? config('seo-manager.route','');
            $options['as'] = $options['as'] ??  config('seo-manager.name', 'seo-manager').'.';
//            $options['namespace'] = $options['namespace'] ??  config('seo-manager.namespace', '\Lionix\SeoManager\Controllers');
            $this->group($options, function () {

                $this->get('/', [ManagerController::class,'index'])->name('home');
                $this->get('get-routes', [ManagerController::class,'getRoutes'])->name('get-routes');
                $this->get('import-routes', [ImportController::class,'index'])->name('import');
                $this->get('get-models', [ManagerController::class,'getModels'])->name('get-models');

                $this->get('sitemaps-generators', [ManagerController::class,'sitemapsGenerators'])->name('sitemaps_generators');

                $this->group(['prefix' => 'locales', 'as' => 'locales.'], function () {
                    $this->get('get-locales', [LocalesController::class,'getLocales'])->name('get');
                });

                $this->post('delete-route', [ManagerController::class,'deleteRoute'])->name('delete-route');
                $this->post('get-model-columns', [ManagerController::class,'getModelColumns'])->name('get-model-columns');
                $this->post('store-data', [ManagerController::class,'storeData'])->name('store-data');
                $this->post('get-example-title', [ManagerController::class,'getExampleTitle'])->name('get-example-title');
                $this->post('sharing-preview', [ManagerController::class,'sharingPreview'])->name('sharing-preview');
                $this->post('set-seo-enable/{class}/{id}/{locale}', [LocalesController::class,'setSeoEnable'])->name('setSeoEnable');

                $this->group(['prefix' => 'locales', 'as' => 'locales.'], function () {

                    $this->post('add-locale', [LocalesController::class,'addLocale'])->name('add');

                });

            });
        };
    }

}
