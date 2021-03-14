<?php

namespace Lionix\SeoManager\Models;

use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    protected $table;

    protected $guarded = ['id'];
//    protected $fillable = ['name'];

    /**
     * Locale constructor.
     */
    public function __construct()
    {
        $this->table = config('seo-manager.database.locales_table');

        parent::__construct();
    }
}
