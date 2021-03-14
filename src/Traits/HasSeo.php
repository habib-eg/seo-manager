<?php

namespace Lionix\SeoManager\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Lionix\SeoManager\Models\SeoEnable;

trait HasSeo
{
    public static function bootHasSeo()
    {
        static::created(function (Model $model) {
            $model->setSeoForAllLocales();
        });
        static::updated(function (Model $model) {
            $model->checkHasSeo();
        });
    }

    /**
     * @return array|int|void
     */
    public function checkHasSeo()
    {
        if (($count = $this->seos()->count()) === 0) {
            return $this->setSeoForAllLocales();
        }
        return $count;
    }

    /**
     * @return MorphMany
     */
    public function seos(): MorphMany
    {
        return $this->morphMany(
            config('seo-manager.morph_class', SeoEnable::class)
            , config('seo-manager.morph_name', 'seotable')
        );

    }

    /**
     * @return array
     */
    public function setSeoForAllLocales(array $locales=null)
    {
        $locales = $locales ?? config('seo-manager.locales', ['ar', 'en'])  ;

        return collect($locales)->merge([app()->getLocale()])->filter()->map(function ($locale){ return $this->setSeoForLocale($locale); })->toArray();
    }

    /**
     * @param string $locale
     * @return Model
     */
    public function setSeoForLocale(string $locale)
    {
        return $this->seos()->firstOrCreate( [ "locale" => $locale ], [ "title" => $this->getTitleValue() , "description" => $this->getDescriptionValue() ]);
    }

    public function getTitleValue()
    {
        return optional($this)->name ?? optional($this)->title ?? optional($this)->slug ?? uniqid('slug', false);
    }


    public function getDescriptionValue()
    {
        return optional($this)->description ?? optional($this)->content ?? optional($this)->body ?? optional($this)->details ?? uniqid('description', false);
    }


    public function setSeoData($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $this->setSeoForAllLocales();
        $seo = $this->seos()->firstWhere('locale',$locale);
        /**
         * @var SeoEnable $seo
         */
        $seo->applySeoData();
        return $seo;
    }

}
