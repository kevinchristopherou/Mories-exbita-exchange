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
    <script src="/js/web3.min.js"></script>
    <script src="/js/web3.connection.js"></script>
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
                            <h3 class="title-h3 color-dark-blue text-center">Download Exbita files</h3>
                            <form action="{{ route('application.activate') }}" method="get" id="eth-connection-form">
                                <div class="form-group">
                                    1. <a target="_blank" href="{{$link}}">Click here to download files</a>
                                </div>
                                <div class="form-group">
                                    2. Unzip downloaded archive and copy all folders/files to project root directory (usually it is /var/www/html). It will ask to replace exiting files, please confirm the replacement.
                                </div>

                                <div class="form-group">
                                    3. Click to the button below to finish the process.
                                </div>

                                <div class="form-group text-center mt-5">
                                    <button type="submit" class="btn btn-success btn-big btn-loading">Done</button>
                                </div>
                            </form>
                            <div class="form-footer">
                                <div><span class="text-danger">PLEASE NOTE: <br /><br /></span>
                                    If you encounter any problems, please contact us by creating a ticket at <a href="https://exbita.com/submit-ticket">Exbita Help Center</a>.
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
