<?php

namespace Lionix\SeoManager\Controllers;

use App\Http\Controllers\Controller;
use Artisan;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input; // Outdated in Laravel 6
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Lionix\SeoManager\Models\SeoManager as SeoManagerModel;
use Lionix\SeoManager\Models\Translate;
use Lionix\SeoManager\Traits\SeoManagerTrait;
use Throwable;

class ManagerController extends Controller
{
    use SeoManagerTrait;

    protected $locale;

    public function __construct()
    {
//        app()->setLocale($request->get('locale'));
        $this->locale =request()->get('locale',app()->getLocale()) ;
    }

    /**
     * @return JsonResponse
     */
    public function getRoutes()
    {
        $routes = SeoManagerModel::all();
        return response()->json(['routes' => $routes]);
    }

    public function sitemapsGenerators()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');
        Artisan::call('route:clear');
        Artisan::call('sitemap:generate');

        return response()->json(['message'=>__('main.generated')]);
    }
    /**
     * @return JsonResponse
     */
    public function getModels()
    {
        try {
            return response()->json(['models' => $this->getAllModels()]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getModelColumns(Request $request)
    {
        try {
            $model = $request->get('model');
            $columns = $this->getColumns($model);
            return response()->json(['columns' => $columns]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeData(Request $request)
    {
        $allowedColumns = Schema::getColumnListing(config('seo-manager.database.table'));
        try {
            $id = $request->get('id');
            $type = $request->get('type');
            $seoManager = SeoManagerModel::findOrFail($id);
            if (in_array($type, $allowedColumns)) {
                $data = $request->get($type);

                if($type != 'mapping' && $this->locale !== config('seo-manager.locale')){
                    $translate = $seoManager->translation()->firstWhere('locale', $this->locale);
                    if(!$translate){
                        $newInst = new Translate();
                        $newInst->locale = $this->locale;
                        $translate = $seoManager->translation()->save($newInst);
                    }
                    $translate->update([$type=>$data]);

                }else{
                    $seoManager->update([$type=>$data]);
                }
            }
            return response()->json([$type => $seoManager]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getExampleTitle(Request $request)
    {
        try {
            $manager = SeoManagerModel::findOrFail($request->id);
            $titles = $request->get('title_dynamic');
            $exampleTitle = $this->getDynamicTitle($titles, $manager);
            return response()->json(['example_title' => $exampleTitle]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteRoute(Request $request)
    {
        try {
            SeoManagerModel::destroy($request->id);
            return response()->json(['deleted' => true]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return array|null
     */
    public function sharingPreview(Request $request)
    {
        $id = $request->get('id');
        $seoManager = SeoManagerModel::findOrFail($id);
        if(is_null($seoManager)){
            return null;
        }
        $ogData = $this->getOgData($seoManager, null);
        return response()->json(['og_data' => $ogData]);
    }
}
