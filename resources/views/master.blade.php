<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.6/css/swiper.min.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clusterize.js/0.18.0/clusterize.min.css">
    <script
        src="https://cdn.tiny.cloud/1/zhx39j2ohsweb3po62947uwrnum0n1xhy3t5ospk0vkgobcs/tinymce/5/tinymce.min.js"></script>
    <script>tinymce.init({selector: 'textarea', height: 500});</script>
    <link rel="stylesheet" href="{{ url('/css/main.css') }}">
    <link rel="stylesheet" href="{{url('/css/loading-bar.css')}}">
    
    @yield('head')
    <title>@yield('title')</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-fixed-top navbar-dark">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li>
                <a class="nav-link" href="/">LotHub</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li>
                <a class="nav-link" id="menu1" href="/">Historically Platted Lots</a>
            </li>
            <li>
                <a class="nav-link " id="menu2" href="/VacantProperties">Vacant Lots</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " id="menu3" href="/location">Address Search </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " id="menu4" href="/person">Person Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " id="menu4" href="/hpl2">hpl2</a>
            </li>
            <li class="nav-item">
                <a href="/savedproperties" target="_blank" id="menu5" class="nav-link">Saved Properties</a>
            </li>
            <li class="nav-item">
                <a href="/logout" class="nav-link">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!--
<div style="
        background-color: red;
        color: white;
        padding: 1px 5px;
        text-align: center;">
    <h1 style="color:white"
    > PLEASE PAY THE DUE AMOUNT</h1>
</div>
-->
<div id="loading"></div>
<div id="isLoaded">


    @yield('content')
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="noteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>LotHub is Currently Undergoing Maintenance. Please Expect Temporary Bugs That Will Disappear Within
                    The Hour.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.6/js/swiper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clusterize.js/0.18.0/clusterize.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.0/js/bootstrap.min.js"></script>
<script src="{{ url('/js/bootstrap-notify.min.js') }}"></script>
<script src="{{ url('/js/cookies.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="module" src=" {{ url('/js/siema.js') }}"></script>
<script src="{{url('/js/loading-bar.js')}}"></script>
<script src=" {{ url('/js/main.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.6/js/swiper.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74&libraries=places,drawing&callback=initAutocomplete"></script>
<script src=" {{ url('/js/notify.js') }}"></script>
@yield('script')

<script type="text/javascript">
    $(window).on('load', function () {
        setTimeout(function () {
            if (!Cookies.get('modalShown')) {
                $("#noteModal").modal('show');
                Cookies.set('modalShown', true);
            }

        }, 30);
    })

    /* $(window).on('load',function(){
         $('#noteModal').modal('show');
     });*/
</script>
</body>
</html>
