<div class="form-group">
    @php($v = uniqid('id',false))
    @php($modal = uniqid('modal',false))

    @if ($label=$attributes->get('label',null))
        @if ($attributes->get('type','text') ==='file')
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal{{$modal}}">
                {{$label}}
            </button>
            <!-- Modal -->
            <div class="modal fade" id="modal{{$modal}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modelLabel{{$modal}}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modelLabel{{$modal}}">@lang('main.preview')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset($attributes->get('value')) }}" id="image{{$modal}}" alt="" class="w-100">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <label for="">{{$label}}</label>
        @endif
    @endif
    @if ($attributes->get('type','text') ==='textarea')
        <textarea id="{{$attributes->get('id',$v)}}" class="form-control @error($attributes->get('name')) is-invalid  @enderror" {{$attributes->except(['value','id'])}}>{{old($attributes->get('name'),$attributes->get('value'))}}</textarea>
    @elseif ($attributes->get('type','text') ==='select')
        <select class="form-control @error($attributes->get('name')) is-invalid  @enderror" {{$attributes->except(['value','id'])}} id="{{$attributes->get('id',$v)}}" value="{{old($attributes->get('name',$attributes->get('value')))}}" >
            {{ $slot  ?? ''}}
        </select>
    @else
        <input class="form-control @error($attributes->get('name')) is-invalid  @enderror" @if($attributes->get('type','text') ==='file') onchange="readURLComponent(this,'image{{$modal}}')" @endif {{$attributes->except(['id'])}} id="{{$attributes->get('id',$v)}}" aria-describedby="helpId{{$attributes->get('id',$v)}}"/>
    @endif

    @error($attributes->get('name'))
        <small id="helpId{{$attributes->get('id',$v)}}" class="form-text text-danger">{{$message}}</small>
    @enderror
</div>
