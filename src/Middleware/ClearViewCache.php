<?php

namespace Lionix\SeoManager\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ClearViewCache
{
    /**
     * Handle an incoming request.
     *]
     * @param  Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Artisan::call('view:clear');
        config(['seo-manager.locale'=>$request->get('locale',app()->getLocale())]);

        return $next($request);
    }
}
