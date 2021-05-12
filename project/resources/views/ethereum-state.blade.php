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
                            <h3 class="title-h3 color-dark-blue text-center">Ethereum Node </h3>
                            <form action="{{ route('ethereum.activate') }}" method="get" id="eth-connection-form">
                                <div class="form-group">
                                    <input id="web3-eth-host" name="license" placeholder="Ethereum Websocket Host e.g. ws://1.1.1.1:8546" type="text" class="form-control input-lg">
                                </div>

                                <div class="form-group text-center mt-5">
                                    <button onclick="setWeb3Connection()" type="button" class="btn btn-orange btn-big btn-loading">Validate Connection</button>
                                </div>
                            </form>
                            <div class="form-footer">
                                <div><span class="text-danger">PLEASE NOTE: <br /><br /></span> We provide fully-synced ETH Node access to our customers. Please contact us by creating a ticket at <a href="https://exbita.com/submit-ticket">Exbita Help Center</a> to get more info.
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
