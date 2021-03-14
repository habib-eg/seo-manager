<div>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @foreach ($locales as $localeItem)
            <li class="nav-item" role="presentation">
                <a class="nav-link {{$loop->first?' active ':''}}" id="pills-{{$localeItem}}-tab" data-toggle="pill" href="#pills-{{$localeItem}}" role="tab" aria-controls="pills-{{$localeItem}}" aria-selected="true">
                    {{__('main.'.$localeItem)}}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="pills-tabContent">
        @foreach ($locales as $localeItem)

            @php

                $modelNew = $$localeItem;
                $namePrefix=$attributes->get('namePrefix') ? $attributes->get('namePrefix').'.':null;
                /**
                 * @var \Lionix\SeoManager\Models\SeoEnable $modelNew
                 */
            @endphp
            <div class="tab-pane fade show {{$loop->first?' active ':''}}" id="pills-{{$localeItem}}" role="tabpanel" aria-labelledby="pills-{{$localeItem}}-tab">
                <form action="{{route($namePrefix.'seo-manager.setSeoEnable',['class'=>$model->getMorphClass(),'id'=>$model->id,'locale'=>$localeItem])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6"> <x-seo-input type="text" name="{{$localeItem}}[title]" value="{{$modelNew->title}}" placeholder="{{__('main.title')}}" label="{{__('main.title')}}"/> </div>
                        <div class="col-md-6"> <x-seo-input type="textarea" rows="5" name="{{$localeItem}}[description]" value="{{$modelNew->description}}" placeholder="{{__('main.description')}}" label="{{__('main.description')}}"/> </div>
                        <div class="col-md-12">
                            <h3>{{__('main.opengraph')}}</h3>

                            <div class="row">
                                <div class="col-md-6"> <x-seo-input type="text" name="{{$localeItem}}[opengraph][title]" value="{{$modelNew['opengraph']['title']??null}}" placeholder="{{__('main.title')}}" label="{{__('main.title')}}"/> </div>
                                <div class="col-md-6"> <x-seo-input type="textarea" rows="5" name="{{$localeItem}}[opengraph][description]"  value="{{$modelNew['opengraph']['description']??null}}" placeholder="{{__('main.description')}}" label="{{__('main.description')}}"/> </div>
                                <div class="col-md-6"> <x-seo-input type="url" name="{{$localeItem}}[opengraph][url]" value="{{$modelNew['opengraph']['url']??null}}" placeholder="{{__('main.url')}}" label="{{__('main.url')}}"/> </div>
                                <div class="col-md-6">
                                    <x-seo-input type="select" name="{{$localeItem}}[opengraph][type]" placeholder="{{__('main.type')}}" label="{{__('main.type')}}">
                                        @foreach ($types as $type)
                                            <option value="{{$type}}"  {{ ($modelNew['opengraph']['type']??null)==$type?' selected ':''}}>
                                                {{__('main.'.$type)}}
                                            </option>
                                        @endforeach
                                    </x-seo-input>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3>{{__('main.json_ld')}}</h3>
                            <div class="row">
                                <div class="col-md-6"> <x-seo-input type="text" name="{{$localeItem}}[json_ld][title]" value="{{$modelNew['json_ld']['title']??null}}"  placeholder="{{__('main.title')}}" label="{{__('main.title')}}"/> </div>
                                <div class="col-md-6"> <x-seo-input type="textarea" rows="5" name="{{$localeItem}}[json_ld][description]" value="{{$modelNew['json_ld']['description']??null}}"  placeholder="{{__('main.description')}}" label="{{__('main.description')}}"/> </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3>{{__('main.json_ld_multi')}}</h3>
                            <div class="row">
                                <div class="col-md-6"> <x-seo-input type="text" name="{{$localeItem}}[json_ld_multi][title]" value="{{$modelNew['json_ld_multi']['title']??null}}" placeholder="{{__('main.title')}}" label="{{__('main.title')}}"/> </div>
                                <div class="col-md-6"> <x-seo-input type="textarea" rows="5" name="{{$localeItem}}[json_ld_multi][description]" value="{{$modelNew['json_ld_multi']['description']??null}}" placeholder="{{__('main.description')}}" label="{{__('main.description')}}"/> </div>
                                <div class="col-md-6"> <x-seo-input type="file" rows="5" name="{{$localeItem}}[json_ld_multi][image]" value="{{$modelNew['json_ld_multi']['image']??null}}" placeholder="{{__('main.image')}}" label="{{__('main.image')}}"/> </div>

                                <div class="col-md-6">
                                    <x-seo-input type="select" name="{{$localeItem}}[json_ld_multi][type]" placeholder="{{__('main.type')}}" label="{{__('main.type')}}">
                                        @foreach ($types as $type)
                                            <option value="{{$type}}"  {{ ($modelNew['json_ld_multi']['type']??null)==$type?' selected ':''}}>
                                                {{__('main.'.$type)}}
                                            </option>
                                        @endforeach
                                    </x-seo-input>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3>{{__('main.twitter')}}</h3>
                            <div class="row">
                                <div class="col-md-6"> <x-seo-input type="text" name="{{$localeItem}}[twitter][title]" value="{{$modelNew['twitter']['title']??null}}" placeholder="{{__('main.title')}}" label="{{__('main.title')}}"/> </div>
                                <div class="col-md-6"> <x-seo-input type="textarea" rows="5" name="{{$localeItem}}[twitter][site]" value="{{$modelNew['twitter']['site']??null}}" placeholder="{{__('main.description')}}" label="{{__('main.description')}}"/> </div>
                                <div class="col-md-6"> <x-seo-input type="file" rows="5" name="{{$localeItem}}[twitter][image]" value="{{$modelNew['twitter']['image']??null}}" placeholder="{{__('main.image')}}" label="{{__('main.image')}}"/> </div>
                            </div>
                        </div>
                        <div class="col-md-12 p-3">
                            <button type="submit" class="btn btn-warning">@lang('main.update')</button>
                        </div>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>

