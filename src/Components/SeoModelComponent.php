<?php
namespace Lionix\SeoManager\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Lionix\SeoManager\Models\SeoEnable;

class SeoModelComponent extends Component
{
    /**
     * @var Model
     */
    public $model;

    /**
     * @var SeoEnable
     */
    public $ar;
    public $locales=[];

    /**
     * @var SeoEnable
     */
    public $en;
    public $types=[];
    /**
     * Create a new component instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model=null)
    {
        $model->checkHasSeo();
        $model->setSeoData();
        $seos= $model->seos;
        $this->locales =count($seos->pluck('locale')->toArray())>0 ? $seos->pluck('locale')->toArray(): config('seo-manager.locales',['ar','en']);
        $this->model = $model;
        foreach ($model->seos as $seo) {
            $this->{$seo->locale} = $seo;
        }
        $this->types = SeoEnable::TYPES;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('seo-manager::components.seo-model');
    }
}
