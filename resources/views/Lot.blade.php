@extends('master')
@section('title','Historically Platted Lots')
@section('content')
    <div class="pt-3">
        <div class="row">
            <div class="col" style="justify-content: center">
                <img src="{{URL::asset('/Img/Lot_Hub_Banner.png')}}" alt="" class="mx-auto d-block">
                <h1 class="center" >Learn How to Find Deals with This Lothub Tutorial Video </h1>
                <h1 class="center"> <a  href="https://www.youtube.com/embed/dqcdh-W53Ag" target="_blank">Click Here To Watch Video Tutorial.</a> </h1>

                <!--
                <div  style="width: 700px;" class="mx-auto d-block">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe  src="https://www.youtube.com/embed/dqcdh-W53Ag" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
                -->
                <br>
                <h1 class="center">Ready to Find Hot Property Deals Nationwide? Enter the Desired Zip-Code Below</h1>

                <h4 class="center"><small> <strong>You have {{$Rcout}} Historic Lot Searches Left. Search Limit will refresh in {{$timeExceed}} </strong></small></h4>

                <br>
                <label class="form-text text-muted center" for="searchByPropForm">Enter the Zip-Code Below to Find Historically Platted Lots</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <div class="input-group  mb-3 search search-reduce" id="searchByPropForm">
                        <input class="form-control" id="search" name="address" type="text" placeholder="By Property"  onFocus="geolocate()" required="true" value="" aria-describedby="searchByProperty"/>
                        <div class="input-group-append">
                            <input class="btn btn-primary" type="button" value="Search" id="searchByProperty">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="searchloading">
        <div
            class="label-center"
            id="ldBar"
            style="width:30%;height:30%;margin:auto"
            data-value="0"
            data-preset="bubble">
        </div>
    </div>
    <div id="issearchdone">
    <div class="row">
        <div class="col-md-6">

            <button class="btn btn-primary" id="clear_polygon" style="display: none;" onclick="resetMarker()">Clear All</button>
            <div class="map" id="map"></div>


            <div class="row">
                <div class="col-md-6 pd-col brd" id="houseDiv">
                    <h2 class="ageDemo mt-30">Housing Inventory</h2>
                    <div class="chart_bar" style="position: relative; margin:0 auto;width:80%; height:150px;" >
                        <div id="chart-1" ></div>
                    </div>
                </div>

                <div class="col-md-6 brd" id="eduDiv">

                    <img src="Img/icon-stat.png" alt="">
                    <h2 class="ageDemo mt-30">Highest education<br>level attained</h2>
                    <h3>Info</h3>
                    <p>The highest education level attained is based on the percentage of eligible graduates within the given population who have achieved the level of education listed.</p>
                    <div class="gap20"></div>

                    <div class="list-row">
                        <span class="list-title">No HS</span>
                        <span class="list-price" id="noHS">  </span>
                    </div>
                    <div class="list-row">
                        <span class="list-title">Some HS</span>
                        <span class="list-price" id="someHS"></span>
                    </div>
                    <div class="list-row">
                        <span class="list-title">HS Grad</span>
                        <span class="list-price" id="hsGrad"></span>
                    </div>
                    <div class="list-row">
                        <span class="list-title">Some College</span>
                        <span class="list-price" id="someCollege"></span>
                    </div>
                    <div class="list-row">
                        <span class="list-title">Associate Degree</span>
                        <span class="list-price" id="associate"></span>
                    </div>
                    <div class="list-row">
                        <span class="list-title">Bachelor's Degreee</span>
                        <span class="list-price" id="bachlor"></span>
                    </div>
                    <div class="list-row">
                        <span class="list-title">Graduate Degreee</span>
                        <span class="list-price" id="graduate"></span>
                    </div>


                </div>
            </div>
            <div class="col-md-12 brd" id="incomeDiv">
                <h2 class="ageDemo mt-30">Income by Households</h2>
                <div class="chart_bar" style="position: relative;height:150px;" >
                    <canvas id="myChart"></canvas>
                </div>
            </div>

        </div>


        <div class="col-md-6 mt-30" id="poiContent">
            <div class="alert alert-dark" role="alert" id="searchCount">
                Count :
            </div>
            <div class="input-group mb-3"  style="padding-right: 10px;">
                <input type="email" id="emailaddress" class=" form-control" placeholder="Enter Email Address" aria-label="Enter Email Address aria-describedby="sendEmail">
                <div class="input-group-append">
                    <button type="button" id="sendEmail" class="btn btn-secondary mb-1"><i class="far fa-envelope"></i></button>
                </div>
            </div>
            <small id="emailHelp" class="form-text text-muted"></small>

            <div class="row">
                <div class="col">
                    <div class="next-slide " id="next-slide"> <button type="button" class="btn btn-outline-dark" style="width: 100%;"><i class="fa fa-arrow-circle-up" aria-hidden="true" style="font-size: 20px;"></i></button></div>
                </div>
                <div class="col">
                    <div class="prev-slide " id="prev-slide"> <button type="button" class="btn btn-outline-dark" style="width: 100%;"><i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 20px;"></i></button></div>
                </div>
            </div>

            <br>

            <div class="swiper-container">


                <div class="swiper-wrapper">



                    <!-- Add Arrows -->
                    <!-- Add Pagination -->

                </div>
                <!--<div class="swiper-button-next"><img src="images/icons/right.png" alt="right"></div>
                <div class="swiper-button-prev"><img src="images/icons/left.png" alt="left"></div>-->
                <div class="swiper-scrollbar"></div>




            </div>
        </div>
    </div>
    </div>
    <div class="modal fade bd-example-modal-xl" id="myModal"  role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Property info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <img id="ModalImg" width="100%" height="400" src="" alt="">
                            </div>
                            <div class="col-6">
                                <div class=" map" id="Modalmap" style="width: 100%;height: 100%">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a target="_blank" id="DetailHref"> <button type="button" class="btn btn-primary">Property Detail</button></a>
                    <button type="button" id="SaveLink" class="btn btn-primary">Save Property</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    
         <!-- myDetailModal -->
        <div class="modal fade bd-example-modal-xl" id="myDetailModal"  role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Property info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid" id="detailBody">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     <!-- myDetailModal -->
    
@endsection
@section('script')

    <!-- Resources -->
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/pie.js"></script>
    <script src="https://www.amcharts.com/lib/3/xy.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

    <script type='text/javascript' src="js/donut-chart.js"></script>
    <script>

        /*
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 10,
            direction: 'vertical',
            slideToClickedSlide: true,
            preventClicks:true,
            on:{
                click: function(swiper, e){
                    // var clicked = $(e.target);
                    //openInfoModal(this.clickedIndex+1);
                    //console.log(clicked);
                },slideChangeTransitionEnd: function (swiper) {
                    focusonmarker(this.activeIndex);

                }
            },
            navigation: {
                nextEl: '.prev-slide',
                prevEl: '.next-slide',
            },
            scrollbar: {
                el: '.swiper-scrollbar',
            },mousewheel: {
                invert: false,
            },
        });

        */
        $('.nav-link').removeClass("active");
        $("#menu1").addClass("active");
        $("input[type='checkbox']").click(function ()  {
            if($('input:checkbox:checked').length > 10)
            {
                alert("Email send limit is 10");
                $(this).attr('checked',false)
            }
        });
    </script>

    <style>
        #chartincomediv {
            width: 100%;
            height: 300px;
        }
        #chart-1 {
            width		: 100%;
            height		: 150px;
            font-size	: 12px;
        }
        #chart-1 a {
            display: none !important;
        }

    </style>
@endsection
