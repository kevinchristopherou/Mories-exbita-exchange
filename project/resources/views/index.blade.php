<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{$favicon}}">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">

    @if(isset($preview))
        <link rel="stylesheet" href="{{ mix('css/vendor.css','site-preview') }}">
        <link rel="stylesheet" href="{{ mix('css/app.css','site-preview') }}">
    @else
        <link rel="stylesheet" href="{{ mix('css/vendor.css') }}">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @endif
    @if($admin)
        <link rel="stylesheet" href="/css/codemirror.css">
    @endif

    <script src="/js/html5shiv.min.js"></script>
    <script src="/js/respond.min.js"></script>
</head>
<body class="index-theme">
<div id="app"></div>
@include('scripts')
</body>
</html>
