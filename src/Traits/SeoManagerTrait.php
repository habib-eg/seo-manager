<?php
/**
 * Created by PhpStorm.
 * User: sergeykarakhanyan
 * Date: 10/30/18
 * Time: 12:00
 */

namespace Lionix\SeoManager\Traits;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Lionix\SeoManager\Models\SeoManager;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;
use ReflectionException;
use Route;
use function GuzzleHttp\Psr7\str;

trait SeoManagerTrait
{
    protected $exceptRoutes = [
        'api',
        'telescope',
        '_debugbar',
        '_ignition',
//        'maileclipse',
//        'acl',
//        'translation-manager',
//        'dashboard',
//        'oauth',
//        'seo-manager'
    ];

    protected $exceptColumns = [
        "password",
        "remember_token",
    ];

    public function __construct()
    {
        $this->exceptRoutes = array_merge($this->exceptRoutes, config('seo-manager.except_routes'));
        $this->exceptColumns = array_merge($this->exceptColumns, config('seo-manager.except_columns'));
    }

    public function index()
    {
        if (request()->ajax()) {
            return SeoManager::all();
        }
        return view('seo-manager::index');
    }

    /**
     * Detect Parameters from the URI
     * @param $uri
     * @return mixed
     */
    private function getParamsFromURI($uri)
    {
        preg_match_all('/{(.*?)}/', $uri, $output_array);

        return $output_array[1];
    }

    /**
     * Check if the given data is URI param
     * @param $param
     * @return bool
     */
    private function isParam($param)
    {
        $pattern_params = '/{(.*?)}/';
        preg_match_all($pattern_params, $param, $output_params);
        if (!empty($output_params[1])) {
            return true;
        }
        return false;
    }

    /**
     * Check if the given data is Title
     * @param $param
     * @return bool
     */
    private function isTitle($param)
    {
        $pattern_title = '/~(.*?)~/';
        preg_match_all($pattern_title, $param, $pattern_title);
        if (!empty($pattern_title[1])) {
            return true;
        }
        return false;
    }

    /**
     * Remove unnecessary characters from param
     * @param $param
     * @return string
     */
    private function cleanParam($param)
    {
        return strtolower(str_replace(['{', '}'], '', $param));
    }

    /**
     * Remove routes which shouldn't be imported to Seo Manager
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    private function cleanRoutes()
    {
        $routes = Route::getRoutes();
        $getRoutes = array_keys($routes->get('GET'));
        foreach ($getRoutes as $key => $route) {
            foreach ($this->exceptRoutes as $rule) {
                if (!empty($rule) && strpos($route, $rule) !== false) {
                    unset($getRoutes[$key]);
                }
            }
        }
        return collect($getRoutes);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getAllModels()
    {
        $path = app_path(config('seo-manager.models_path')) ;
        $models = File::allFiles($path);
        $cleanModelNames = [];
        foreach ($models as $model) {
            $modelPath = $this->cleanFilePath($model);
            $reflectionClass = (new ReflectionClass($modelPath))->getParentClass();
            if ($reflectionClass !== false) {

                if (in_array($reflectionClass->getName(),config('seo-manager.models_extends_class'))) {
                    $cleanModel = [
                        'path' => $modelPath,
                        'name' => str_replace('.php', '', $model->getFilename())
                    ];
                    array_push($cleanModelNames, $cleanModel);
                }
            }
        }
        return $cleanModelNames;
    }

    /**
     * Get Model all Columns
     * @param $model
     * @return array
     */
    public function getColumns($model)
    {
        $appends = [];
        if (method_exists((new $model), 'getAppends')) {
            $appends = (new $model)->getAppends();
        }
        $table = (new $model)->getTable();
        $columns = $this->getCleanColumns(Schema::getColumnListing($table));
        return array_merge($columns, $appends);
    }

    /**
     * Clean model file path
     * @param $file
     * @return string
     */
    private function cleanFilePath($file)
    {
        return '\\' . ucfirst(str_replace('/', '\\', substr($file, strpos($file, 'app'), -4)));
    }

    /**
     * Remove unnecessary columns from table columns list
     * @param $columns
     * @return array
     */
    private function getCleanColumns($columns)
    {
        return array_diff($columns, $this->exceptColumns);
    }

    /**
     * Import Routes to SeoManager database
     * @return array|Collection|static[]
     */
    private function importRoutes()
    {

        $this->cleanRoutes()->map(function ($route) {
            return SeoManager::updateOrCreate(['uri' => (string)$route],
                [
                    'uri' => (string)$route,
                    'url' => (string)$route,
                    'title' => str_replace('/', ' ', (string)$route),
                    'params' => $this->getParamsFromURI($route),
                    'keywords' => [],
                    'title_dynamic' => []
                ]);
        });

        return SeoManager::all();
    }

    /**
     * Get mapped Seo Data from Database for Current Route
     * @param $property
     * @return mixed
     */
    private function getMetaData($property)
    {
        $route = Route::current();
        if (is_null($route)) {
            return null;
        }
        $uri = $route->uri();
        $seoManager = SeoManager::firstWhere('uri', $uri);
        if (is_null($seoManager)) {
            return null;
        }
        $metaData = [];
        if (count($seoManager->keywords) > 0) {
            $metaData['keywords'] = implode(', ', $seoManager->keywords);
        }
        if ($seoManager->description) {
            $metaData['description'] = $seoManager->description;
        }
        if ($seoManager->title) {
            $metaData['title'] = $seoManager->title;
        }
        if ($seoManager->url) {
            $metaData['url'] = $seoManager->url;
        } else {
            $metaData['url'] = url()->full();
        }
        if ($seoManager->author) {
            $metaData['author'] = $seoManager->author;
        }
        if ($seoManager->mapping !== null) {
            $metaData['title_dynamic'] = $this->getDynamicTitle($seoManager->title_dynamic, $seoManager, $route->parameters);
        }
        if ($seoManager->og_data) {
            $ogData = $this->getOgData($seoManager, $route->parameters);
            if ($property === 'og_data') {
                $metaData['og_data'] = $ogData;
            } else {
                foreach ($ogData as $key => $og) {
                    $metaData[$key] = $og;
                }
            }
        }

        if ($property !== null && !empty($property)) {
            if (isset($metaData[$property])) {
                return $metaData[$property];
            } else {
                return null;
            }
        }
        return $metaData;
    }

    /**
     * Get dynamic title based on user configs for current route
     * @param $params
     * @param $manager
     * @param $routeParams
     * @return string
     */
    private function getDynamicTitle($params, $manager, $routeParams = null)
    {
        $dynamicTitle = '';
        if (is_array($params)) {
            foreach ($params as $param) {
                if ($this->isParam($param)) {
                    $param = $this->cleanParam($param);
                    $paramsArray = explode('-', $param);
                    $mapping = $manager->mapping[$paramsArray[0]];
                    $model = $mapping['model']['path'];
                    $findBy = $mapping['find_by'];
                    $selectedColumns = $mapping['selectedColumns'];
                    if (in_array($paramsArray[1], $selectedColumns)) {
                        $mappedTitle = (new $model);
                        if ($routeParams) {
                            $mappedTitle = $mappedTitle->firstWhere($findBy, $routeParams[$paramsArray[0]]);
                        } else {
                            $mappedTitle = $mappedTitle->first();
                        }
                        if ($mappedTitle) {
                            $dynamicTitle .= optional($mappedTitle)->{$paramsArray[1]} . ' ';
                        }
                    }
                } elseif ($this->isTitle($param)) {
                    $dynamicTitle .= $manager->title . ' ';
                } else {
                    $dynamicTitle .= $param . ' ';
                }
            }
        }
        return $dynamicTitle;
    }

    /**
     * Get Open Graph Dynamic Data
     * @param $seoManager
     * @param $routeParams
     * @return array
     */
    private function getOgData($seoManager, $routeParams):array
    {
        /**
         * @var SeoManager $seoManager
         */
        if (is_null($seoManager->og_data) || ( !isset($seoManager['og_data']['og_data']) || !isset($seoManager['og_data']['title']['data']))) return [] ;
        $dataArray = [];
        $value = '';
        foreach ($seoManager['og_data']['og_data'] as $key => $og) {
            if (is_array(reset($og['data']))) {
                foreach ($og['data'] as $ogKey => $data) {
                    if ($data['mapped'] && $data['value']) {
                        $value = $this->getMappedValue($data['value'], $seoManager, $routeParams);
                    } elseif ($data['value']) {
                        $value = $data['value'];
                    }
                    if ($data['value']) {
                        $dataArray['og:' . $key . ':' . $ogKey] = $value;
                    }
                }
            } else {
                if ($og['data']['mapped'] && $og['data']['value']) {
                    $value = $this->getMappedValue($og['data']['value'], $seoManager, $routeParams);
                } elseif ($og['data']['value']) {
                    $value = $og['data']['value'];
                } elseif ($key === 'url') {
                    $value = url()->full();
                }
                if ($og['data']['value'] || $key === 'url') {
                    $dataArray['og:' . $key] = $value;
                }
            }



        }
        return $dataArray;
    }

    /**
     * Get Open Graph Data Values based on Mapped Params
     * @param $value
     * @param $manager
     * @param $routeParams
     * @return mixed
     */
    private function getMappedValue($value, $manager, $routeParams)
    {

        $paramsArray = explode('-', strtolower($value));
        $mapping = $manager->mapping[$paramsArray[0]];
        $model = $mapping['model']['path'];
        $findBy = $mapping['find_by'];
        $selectedColumns = $mapping['selectedColumns'];
        $mapped = null;
        if (isset($paramsArray[1]) && in_array($paramsArray[1], $selectedColumns)) {
            $mapped = (new $model);
            if ($routeParams) {
                $mapped = $mapped->firstWhere($findBy, $routeParams[$paramsArray[0]]);
            }else{
                $mapped = $mapped->first();
            }
        }
        return optional($mapped)->{$paramsArray[1]};
    }
}
