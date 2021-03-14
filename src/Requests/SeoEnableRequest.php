<?php

namespace Lionix\SeoManager\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lionix\SeoManager\Models\SeoEnable;

/**
 * Class SeoEnableRequest
 * @package Lionix\SeoManager\Requests
 */
class SeoEnableRequest extends FormRequest
{
    public function rules()
    {
        $item=$this->route()->parameter('locale');

        $rules= [
            $item                               =>      ['required','array'],
            $item.".title"                      =>      [ "nullable","string" ],
            $item.".description"                =>      [ "nullable","string" ],
            $item.".type"                       =>      [ "nullable","string",Rule::in(SeoEnable::TYPES??[]) ],
            $item.".opengraph.title"            =>      [ "nullable","string" ],
            $item.".opengraph.description"      =>      [ "nullable","string" ],
            $item.".opengraph.type"             =>      [ "nullable","string",Rule::in(SeoEnable::TYPES??[]) ],
            $item.".opengraph.url"              =>      [ "nullable","url" ],
            $item.".opengraph.site_name"        =>      [ "nullable","string" ],
            $item.".json_ld.title"              =>      [ "nullable","string" ],
            $item.".json_ld.description"        =>      [ "nullable","string" ],
            $item.".json_ld_multi.title"        =>      [ "nullable","string" ],
            $item.".json_ld_multi.description"  =>      [ "nullable","string" ],
            $item.".json_ld_multi.image"        =>      [ "nullable","image" ],
            $item.".json_ld_multi.type"         =>      [ "nullable","string",Rule::in(SeoEnable::TYPES??[]) ],
            $item.".twitter.title"              =>      [ "nullable","string" ],
            $item.".twitter.site"               =>      [ "nullable","string" ],
            $item.".twitter.image"              =>      [ "nullable","image" ]
        ];
        return $rules;
    }

    public function authorize()
    {
        return true;
    }
}
