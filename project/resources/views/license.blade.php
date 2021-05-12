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

    <link rel="stylesheet" href="{{ mix('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="/js/html5shiv.min.js"></script>
    <script src="/js/respond.min.js"></script>
</head>
<body>
<div id="app">
    <div>
        <header id="inner-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="logo">
                            <div class="brand-image-heading active">
                                <img src="/exbita-logo.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <main class="inner-container">
                            <h3 class="title-h3 color-dark-blue text-center">License Activation</h3>
                            <form action="{{ route('license.activate') }}" method="get">
                                @error('license')
                                    <div class="form-group has-error">
                                        <label class="control-label">{{ $message }}</label>
                                    </div>
                                @enderror
                                <div class="form-group @error('license') has-error @enderror">
                                    <input name="license" placeholder="Enter license key" type="text" class="form-control input-lg">
                                </div>
                                <div class="form-group text-center mt-4">
                                    <button type="submit" class="btn btn-orange btn-big btn-loading">Activate</button>
                                </div>
                            </form>
                            <div class="form-footer">
                                <div><span class="text-danger">PLEASE NOTE:</span> ONE License is bound to ONE domain. Using the same license to other domains is not allowed.
                                    <br />
                                    <span class="text-danger">Otherwise, your license will be revoked.</span>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
</html>
