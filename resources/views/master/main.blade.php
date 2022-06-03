<!doctype html>
<html lang="en">
<head>
    <link rel="icon" href="{{asset('/img/redpill_fav.png') }}" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/css/custom.css')}}">
    @hasSection('title')
        <title>{{config('app.name')}} - @yield('title')</title>
    @else
        <title>{{config('app.name')}}</title>
    @endif

    <style>
        .product_container .card-body{ padding-top: 0}
        .product_container h4{ min-height: 67px;}
        .shippping_container{
            position: absolute;
            top: 205px;
            background: rgb(0, 128, 96);
            color: #fff !important;
            padding: 5px;
            padding-left: 10px;
        }
        .row_product .shippping_container{
            position: relative;
            top: 0;
            padding: 0 !important;
            }
        .shippping_container b{
            color: #fff;
            }
    </style>

</head>
<body class="pb-4">
    @include('master.navbar')
    @include('master.search')

    @hasSection('container-fluid')
        <div class="container-fluid">
    @else
                <div class="container">
    @endif

    @include('includes.jswarning')

    <div class="mt-4">
        @yield('content')
    </div>

    @include('footer')

            </div>
</body>
</html>
