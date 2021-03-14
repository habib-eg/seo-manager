<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{app()->getLocale()==='ar' ? 'trl': 'ltr'}}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{setting('app_name',config('app.name',env('APP_NAME','SEO')))}}</title>

    <!-- Libs CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/lionix/fonts/feather/feather.min.css') }}">
    @if (app()->getLocale()==='ar')

        <!-- Theme CSS -->
        <link rel="stylesheet" href="{{ asset('vendor/lionix/css/theme.min.rtl.css') }}">
    @else
        <!-- Theme CSS -->
        <link rel="stylesheet" href="{{ asset('vendor/lionix/css/theme.min.css') }}">
    @endif
    <meta name="csrf-token" content="{{ $csrf = csrf_token() }}">
    <meta name="path" content="{{ url('')  }}">
    <script>
        let API_URL = '{{ action(['\Lionix\SeoManager\Controllers\ManagerController','index']) }}';
        let API_ASSETS = '{{ url('') }}';
        let CSRF_TOKEN = '{{ $csrf }}';
        window.title ="{{setting('app_name')}}";
    </script>

</head>
<body>

<div id="lionix-seo-manager-app">
    {{--Main Content--}}
    <app></app>

</div>

<script>
    localStorage.setItem('locale','{{app()->getLocale()}}')
    var Laravel = {locales:@json(config('seo-manager.locales',['en','ar']))};
</script>
<!-- JAVASCRIPT
================================================== -->
<!-- Libs JS -->
<script src="{{ asset('vendor/lionix/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/lionix/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- Theme JS -->
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
</script>
{{--<script src="{{ asset('vendor/lionix/js/theme.min.js') }}"></script>--}}
<script src="{{ asset('vendor/lionix/js/seo-manager.app.js') }}"></script>

</body>

</html>
