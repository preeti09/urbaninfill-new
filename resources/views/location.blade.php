@extends('master')
@section('title','Address Search')
@section('head')
    <link rel="stylesheet" href="css/school.css">
@endsection
@section('content')

    <div class="container pt-3 ">
        <div class="row">
            <div class="col-md-7 col-xs-12 col-md-offset-3">
                <h4><small> <strong>You have {{$Rcout}} Detailed Address Searches Left. Search Limit will refresh
                            in {{$timeExceed}} </strong></small></h4>
                <br>
                <div class="form-group">
                    <label class="form-text text-muted" for="searchByPropForm">Enter Address for Property
                        Details</label>
                    <div class="input-group mb-3 search search-reduce" id="searchByPropForm">
                        <input class="form-control searchfield" id="searchAddress" name="address" type="text"
                               placeholder="By Property" onFocus="geolocate()" required="true" value=""
                               aria-describedby="searchByAddress"/>
                        <div class="input-group-append">
                            <input class="btn btn-primary" type="button" value="Search" id="searchByAddress">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="searchloading"></div>
        <div id="issearchdone">
            <div class="col-md-8 col-xs-12 mt-30 ">
                <h3>
                    Legal Description
                </h3>
                <p class="lead" id="LegalAddress">

                </p>
                <a href="" id="detailviewBTN" class="btn btn-primary btn-sm">Click here for detail</a>
            </div>


        </div>

    </div>
    <div class="row">
        <div class="col-10" id="view">

        </div>
    </div>
    </div>

@endsection


@section('script')
    <script>
        $('#detailviewBTN').hide();
        $('.nav-link').removeClass("active");
        $("#menu3").addClass("active");
    </script>
@endsection
