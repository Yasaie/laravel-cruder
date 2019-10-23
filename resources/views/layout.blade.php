<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($route) and $route and Route::has("$route.index"))
        <meta name="route" content="{{ route("$route.index")}}">
    @endif

    <title>پنل مدیریت | @yield('title')</title>

    @include('Cruder::layout.styles')
</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

    @include('Cruder::layout.navbar')

    @include('Cruder::layout.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">@yield('title')</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">

                @yield('body')

            </div>
        </section>
    </div>
</div>

@include('Cruder::layout.scripts')

</body>
</html>
