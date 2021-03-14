<?php

namespace Lionix\SeoManager\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SeoManager extends Model
{
    protected $table;
    protected $locale;
    protected $guarded=['id'];
//    protected $fillable = [
//        'uri',
//        'params',
//        'mapping',
//        'keywords',
//        'description',
//        'title',
//        'author',
//        'url',
//        'title_dynamic',
//        'og_data'
//    ];

    protected $casts = [
        'params' => 'array',
        'mapping' => 'array',
        'keywords' => 'array',
        'title_dynamic' => 'array',
        'og_data' => 'array',
    ];

    public function __construct()
    {
        $this->table = config('seo-manager.database.table');
        $this->locale = request('locale',app()->getLocale());

        parent::__construct();
    }


    public function translation()
    {
        return $this->hasOne(Translate::class, 'route_id', 'id')->where('locale',$this->locale  );
    }

    /**
     * @return bool
     */
    private function isNotDefaultLocale()
    {
        return $this->locale !== config('seo-manager.locale') && $this->has('translation');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getKeywordsAttribute($value)
    {
        if ($this->condition('keywords')) {
            return $this->translation->keywords;
        }
        return json_decode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getDescriptionAttribute($value)
    {
        if ($this->condition('description')) {
            return $this->translation->description;
        }
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getTitleAttribute($value)
    {
        if ($this->condition('title')) {
            return $this->translation->title;
        }
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getAuthorAttribute($value)
    {
        if ($this->condition('author')) {
            return $this->translation->author;
        }
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getUrlAttribute($value)
    {
        if ($this->condition('url')) {
            return $this->translation->url;
        }
        return $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getTitleDynamicAttribute($value)
    {
        if ($this->condition('title_dynamic')) {
            return $this->translation->title_dynamic;
        }
        return json_decode($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getOgDataAttribute($value)
    {
        if ($this->condition('og_data')) {
            return $this->translation->og_data;
        }
        return json_decode($value, true);
    }

    public function condition(string $column):bool
    {
        return $this->isNotDefaultLocale() &&( !is_null(optional($this->translation))->{$column} || ! empty(optional($this->translation)->{$column}) );
//        return $this->isNotDefaultLocale() &&( !is_null(optional($this->translation))->{$column} || ! empty(optional($this->translation)->{$column}) );
    }
}
