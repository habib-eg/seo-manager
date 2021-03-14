<?php


namespace Lionix\SeoManager\Models;


use Habib\Settings\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Lionix\SeoManager\Traits\SeoEnableModel;

class SeoEnable extends Model
{
    const TYPES=[ "image" , "audio" , "book" , "video" , "article" , "profile" ];
    const ARRASES=["metatags", "opengraph", "json_ld", "json_ld_multi", "twitter",];

    use SeoEnableModel,UuidTrait;



}
