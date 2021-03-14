<?php
namespace Lionix\SeoManager\Traits;

use Artesaos\SEOTools\Facades\SEOTools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Lionix\SeoManager\Models\SeoEnable;
use Psy\Util\Str;

trait SeoEnableModel
{

    public static function bootSeoEnableModel()
    {
        static::created(function (Model $model) {
            $model->update($model->mergeDefaultAttribute());
        });
    }

    /**
     * @return array
     */
    public function mergeDefaultAttribute():array
    {
        $seoArray['opengraph'] = [
            "title" => null,
            "description" => null,
            "url" => null,
            "type" => null,
            "locale" => null,
            "site_name" => null,
            "properties" => [],
            "values" => [],
            "images" => [],
            "musics" => [],
            "audios" => [],
            "videos" => [],
        ];
        $seoArray['metatags'] = [
            "title" => null,
            "description" => null,
            "canonical" => null,
            "keywords" => [],
            "metas" => [],
        ];
        $seoArray['json_ld'] = [
            "title" => null,
            "description" => null,
        ];
        $seoArray['json_ld_multi'] = [
            "title" => null,
            "description" => null,
            "image" => null,
            "type" => null,
        ];
        $seoArray['twitter'] = [
            "title" => null,
            "site" => null,
            "image" => null,
        ];
        return (array)$seoArray;
    }

    public function initializeSeoEnableModel()
    {
        $this->setTable(config('seo-manager.database.enable_table', 'seo_enables'));
        $this->fillable = array_merge($this->fileable ?? [], ['title', 'description', 'metatags', 'opengraph', 'json_ld', 'json_ld_multi', 'twitter', 'type', 'seotable_id', 'seotable_type', 'locale']);
        $this->casts = array_merge($this->casts ?? [], ["metatags" => "array", "opengraph" => "array", "json_ld" => "array", "json_ld_multi" => "array", "twitter" => "array",]);

    }

    /**
     * @return MorphTo
     */
    public function seotable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return $this
     */
    public function applySeoData()
    {
        SEOTools::setTitle($this->title);
        SEOTools::setDescription($this->description);
        foreach (SeoEnable::ARRASES as $ARRASE) {
            if (in_array($ARRASE,['json_ld','json_ld_multi'])) {
                $ARRASE = \Str::studly($ARRASE);
            }
            $this->selectTypeSeo($ARRASE, $ARRASE);
        }
        return $this;
    }

    /**
     * @param $name
     * @param $type
     */
    public function selectTypeSeo($name, $type)
    {
//        try {
        if (is_string($this->{$name})) {
            $this->update([
                $name => (array)json_decode($this->{$name}, true)
            ]);
        }

        foreach ($this->{$name} ?? [] as $key => $item) {
            if (in_array($key,[ 'description', 'title', 'url', 'type', 'canonical','keywords' ]) && $item) {
                $upper = 'set'.\Str::ucfirst($key);
                SEOTools::{$type}()->{$upper}($item);
            }else{
                if ($key == "description" && isset($item) && !empty($item)) {
                    SEOTools::{$type}()->setDescription($item ?? $this->description);
                }
                if ($key == "title" && isset($item) && !empty($item)) {
                    SEOTools::{$type}()->setTitle($item ?? $this->title);
                }
            }

//            if ($key == "description" && isset($item) && !empty($item)) {
//
//                SEOTools::{$type}()->setDescription($item);
//            }
//            if ($key == "title" && isset($item) && !empty($item)) {
//                SEOTools::{$type}()->setTitle($item);
//            }
//            if ($key == "url" && isset($item) && !empty($item)) {
//                SEOTools::{$type}()->setUrl($item);
//            }
//            if ($key == "type" && isset($item) && !empty($item)) {
//                SEOTools::{$type}()->setType($item);
//            }
        }
//        } catch (\Exception $exception) {
//            dd($type,$name);
//        }
    }
}
