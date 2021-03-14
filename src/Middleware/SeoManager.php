<?php

namespace Lionix\SeoManager\Middleware;

use Closure;
use Illuminate\Http\Request;
use View;

class SeoManager
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
        View::share('metaData', metaData());
        $name=metaTitle() ??  setting('app_name');
        $description= metaDescription() ?? setting('app_description','description description description description description description ');
        foreach (['meta', 'opengraph', 'json-ld'] as $item)  config(['seotools.'.$item.'.defaults.title'=>$name,'seotools.'.$item.'.defaults.description'=>$description]);
        config(['app.name'=>$name]);
//        SEOTools::setDescription($description);
//        SEOTools::setTitle($name);
        return $next($request);
    }
}
