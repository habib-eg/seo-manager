<?php

namespace Lionix\SeoManager\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Lionix\SeoManager\Models\Locale;
use Lionix\SeoManager\Models\SeoEnable;
use Lionix\SeoManager\Requests\SeoEnableRequest;

class LocalesController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function getLocales()
    {
//        $locales = Locale::pluck('name');
        return response()->json(['locales' => config('seo-manager.locales',['ar','en'])]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addLocale(Request $request)
    {
        try{
            $locale = Locale::whereName($request->get('name'))->first();
            if(!$locale){
                $locale = new Locale();
                $locale->fill($request->all());
                $locale->save();
                return response()->json(['locale' => $locale->name]);
            }
            throw new Exception('Locale is already exist');
        }catch (Exception $exception){
            return response()->json(['status' => false, 'message' => $exception->getMessage()], 400);
        }
    }

    public function setSeoEnable(SeoEnableRequest $request,$class,$id,$locale)
    {
        $model = $class::findOrFail($id);
        $validated= $request->validated()[$locale];

        if ($request->hasFile($locale.'.twitter.image')) {
            $validated['twitter']['image'] = $this->uploader($request->file($locale.'.twitter.image'));
        }
        if ($request->hasFile($locale.'.json_ld_multi.image')) {
            $validated['json_ld_multi']['image'] = $this->uploader($request->file($locale.'.json_ld_multi.image'));
        }
        if ($seo = $model->seos()->where('locale',$locale)->first()) {
            $seo->update($validated);
        }else{
            $model->seos()->create($validated);
        }
        return back()->withSuccess('SEO');
    }

    public function uploader(UploadedFile $image):string
    {
        $path = rtrim('', '/');
        $filename = uniqid(config('app.name',env('APP_NAME','app')),false) . '.' . $image->getClientOriginalExtension();
        $image->storeAs($path, $filename);
        return str_replace('//', '/', 'uploads/' . $path . '/' . $filename);
    }
}
