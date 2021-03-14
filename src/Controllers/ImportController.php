<?php

namespace Lionix\SeoManager\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Lionix\SeoManager\Traits\SeoManagerTrait;

/**
 * Class ImportController
 * @package Lionix\SeoManager
 */
class ImportController extends Controller
{
    use SeoManagerTrait;

    /**
     * Import routes to the SeoManager database table
     * @return JsonResponse
     */

    public function index()
    {
        try {
            $routes = $this->importRoutes();

            return response()->json(['routes' => $routes]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }
}
