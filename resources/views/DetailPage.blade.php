@extends('master')
@section('title','Detail Information')
@section('content')



    <script>
        let a = {!! json_encode($AVMResult) !!};

        console.log( a );

    </script>

    <div id="searchloading">
        <div
            class="label-center"
            id="ldBar"
            style="width:30%;height:30%;margin:auto"
            data-value="0"
            data-preset="bubble">
        </div>
    </div>
    <?php $postalcode = $result['property'][0]['address']['postal1']; ?>
    <div id="proResponse">
        @if($AVMResult["property"])
            @foreach ($AVMResult["property"] as $key => $WholePropertydata)
                <div class="card">
                    <div class="card-header">
                        <a  href="/propertymail/{{$fullname}}/{{$fulladdress}}/abc@email.com/{{\App\Mailertemplate::first()->id}}" target="_blank"  class="btn btn-dark" style="float: right;" id='MailerBtn'>E-Mail Property Owner</a>
                        <h4>{{$WholePropertydata["address"]["line1"]}}</h4>
                        <h7>{{$WholePropertydata["address"]["line2"]}}</h7>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-6">
                                <img width="100%" height="400" src="https://maps.googleapis.com/maps/api/streetview?size=800x400&location={{$fulladdress}}&pitch=0&fov=120&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">
                            </div>
                            <div class="col-6">
                                <div class=" map" id="Mymap" style="width: 100%;height: 100%">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">

                                <div class="card">
                                    <div class="card-header">
                                        <h5>ASSESSOR</h5>
                                    </div>
                                    <div class="card-body">
                                        <table cellspacing="0" style="border-collapse:collapse;">
                                            <tbody>
                                            <tr>
                                                <td>

                                                    <strong>Address1: </strong>
                                                    <span>{{$WholePropertydata["address"]["line1"]}}</span>
                                                    <br>
                                                    <strong>Address2: </strong>
                                                    <span>{{$WholePropertydata["address"]["line2"]}}</span>
                                                    <br>
                                                    <strong>City: </strong>
                                                    <span>{{$WholePropertydata["address"]["locality"]}}</span>
                                                    <br>
                                                    <strong>Property ID: </strong>
                                                    <span>{{$WholePropertydata["identifier"]["apnOrig"]}}</span>
                                                    <br>
                                                    <strong>Tax Roll: </strong>
                                                    <span>{{$WholePropertydata["summary"]["legal1"]}}</span>
                                                    <br>
                                                    <strong>Use: </strong>
                                                    <span>{{$WholePropertydata["summary"]["propclass"]}}</span>
                                                    <br>
                                                    <strong>Block: </strong>
                                                    <span>{{$WholePropertydata["area"]["blockNum"]}}</span>
                                                    <br>
                                                    <strong>Country: </strong>
                                                    <span>{{$WholePropertydata["area"]["munname"]}}</span>
                                                    <br>
                                                    <strong>Total Land Area: </strong>
                                                    <span>{{$WholePropertydata["lot"]["lotsize1"]}} acres ({{$WholePropertydata["lot"]["lotsize2"]}} sq ft)</span>
                                                    <br>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h5>OWNER</h5>
                                    </div>
                                    <div class="card-body">
                                        <table cellspacing="0" style="border-collapse:collapse;">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <?php $count = 0 ?>
                                                    @foreach ($WholePropertydata["owner"] as $key => $value)
                                                        @if(gettype($value) == "array" )
                                                            @if(count($value) >0 || !empty($value) || $value != null )
                                                                <?php $name = "";
                                                                    $count++;?>
                                                                @foreach ($value as $k => $v)
                                                                    @if($v == "")
                                                                        @continue
                                                                    @endif
                                                                        <?php $name = $name." ". $v;?>
                                                                @endforeach
                                                                    @if($name != "")
                                                                        <strong>Owner {{$count}} </strong>
                                                                        <br/>
                                                                        <strong>Name : </strong>
                                                                        <span >{{$name}}</span>
                                                                        <br/>
                                                                    @endif

                                                            @endif
                                                            <hr/>
                                                        @else
                                                            @if($key == "mailingaddressoneline")
                                                                <strong>Address : </strong>
                                                                <span >{{$value}}</span>
                                                                <br>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                            </tr>
                                                <!-- Add IDe Core code here !!!! -->


                                            <!-- IDI code ends -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">

                                <div class="card">
                                    <div class="card-header">
                                        <h4>MORTGAGE</h4>
                                    </div>
                                    <div class="card-body">
                                        <table  cellspacing="0" style="border-collapse:collapse;">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    @if($WholePropertydata["mortgage"])
                                                        @foreach ($WholePropertydata["mortgage"] as $key => $value)
                                                            @if(gettype($value) != "array")
                                                                <strong>{{$key}} : </strong>
                                                                <span >{{$value}}</span>
                                                                <br>
                                                            @else
                                                                <strong>{{$key}}  </strong>
                                                                <br/>
                                                                @foreach ($value as $k => $v)
                                                                    <strong>{{$k}} : </strong>
                                                                    <span >{{$v}}</span>
                                                                    <br>
                                                                @endforeach
                                                                <hr/>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                @if(count($Assessment) >0)
                                <div class="card">
                                    <div class="card-header">
                                    <h4>ASSESSMENT HISTORY</h4>
                                </div>
                                    <div class="card-body">
                                        <table cellspacing="0" class="table" style="border-collapse:collapse;">
                                            <thead>
                                                <th>Year</th>
                                                <th>Improvements</th>
                                                <th>Land</th>
                                                <th>Real Market</th>
                                            </thead>

                                        <tbody>
                                        @foreach($Assessment as $key => $assessmentValue)

                                            @foreach($assessmentValue as $historyValue)
                                                <tr>
                                                    <td>
                                                        {{$historyValue["tax"]["taxyear"]}}
                                                    </td>
                                                    <td>
                                                        ${{$historyValue["market"]["mktimprvalue"]}}
                                                    </td>
                                                    <td>
                                                        ${{$historyValue["market"]["mktlandvalue"]}}
                                                    </td>
                                                    <td>
                                                        ${{$historyValue["market"]["mktttlvalue"]}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                        <tr>

                                        </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    
<div class="modal fade bd-example-modal-xl" id="MarkerModal"  role="dialog">
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
                                <div class=" map" id="newModalmap" style="width: 100%;height: 100%">

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
@endsection


@section('script')
<script>
var styles = {
    default: null,
    hide: [
        {
            featureType: 'poi',
            elementType: "labels.icon",
            stylers: [{visibility: 'off'}]
        },
        {
            featureType: 'poi',
            elementType: "labels.text",
            stylers: [{visibility: 'off'}]
        },
        {
            featureType: 'road',
            elementType: "labels.text",
            stylers: [{visibility: 'off'}]
        },
        {
            featureType: 'transit',
            elementType: 'labels.icon',
            stylers: [{visibility: 'off'}]
        },
        {
            featureType: 'transit',
            elementType: 'labels.text',
            stylers: [{visibility: 'off'}]
        }
    ]
};    
init();
bar1 = new ldBar("#ldBar");
bar1.set(0);
$('.card').css("display", "none");
$('#searchloading').css("display", "block");
var map;
function init() {

    
    const myOptions = {
        enableHighAccuracy: true,
        zoom: 19,
        center: new google.maps.LatLng( "{{$lat}}" ,"{{$longi}}"),
        scrollwheel:true
    };
     map = new google.maps.Map(document.getElementById("Mymap"), myOptions);

    const marker = new google.maps.Marker({
        position: new google.maps.LatLng("{{$lat}}" ,"{{$longi}}"),
        map: map,
        animation: google.maps.Animation.DROP
    });
}

getlist("{{$postalcode}}","{{$lat}}","{{$longi}}",false);
let count_request = 0;
function getlist(postalcode,lat, lng, isVacant) {
    
    fetch(buildUrl('/getTotalPages', { zip: postalcode, isVacant: isVacant}), {
        method: "get", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        headers: {
            "Content-Type": "application/json",
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // "Content-Type": "application/x-www-form-urlencoded",
        },
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    })
        .then(function (response) {
            if (response.status >= 200 && response.status < 300) {
                return response.json()
            }
            throw new Error(response.statusText)
        })
        .then(function (data) {
            if (data === "Error") {
                if (!isVacant)
                    $.notify('You exceed the search limit in Historic lot', 'error');
                else
                    $.notify('You exceed the search limit in Vacant lot', 'error')

                return
            } else {
                totalPages = Math.ceil(data);
                for (let i = 1; i <= totalPages; i++) {
                    postData('/allpropertiesList', {
                        lat: lat,
                        lng: lng,
                        page: i,
                        zip: postalcode
                    }, isVacant);
                }

            }
        });
}

function postData(url = ``, data = {}, isVacant) {
    // Default options are marked with *
    fetch(buildUrl(url, data), {
        method: "get", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        headers: {
            "Content-Type": "application/json",
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // "Content-Type": "application/x-www-form-urlencoded",
        },
        redirect: "follow", // manual, *follow, error
        referrer: "no-referrer", // no-referrer, *client
    })
        .then(function (response) {
            if (response.status >= 200 && response.status < 300) {
                return response.json()
            }
            throw new Error(response.statusText)
        })
        .then(function (data) {
            
            count_request++;
            let validPropertyList = [];
            let location = [];
            // totalPages = data.property.length;
            if (data) {
                bar1.set((count_request / parseInt(totalPages)) * 100);
                if (count_request == totalPages) {
                    $("#searchloading").fadeOut("slow", function () {
                        $('#searchloading').css("display", "none");

                        $(".card").css("display", "block");
                    });
                }
                postalcode = "{{$postalcode}}";
                if (typeof (data) === typeof ('d'))
                    return;
                for (const [i, property] of data.property.entries()) {

                   
                    if (property["address"]["postal1"] != postalcode)
                        continue;
                    let visited = '';

                    const address = encodeURI(property["address"]["line1"]) + encodeURI(property["address"]["line2"]);


                    if (b_check_visited_links(address))
                        visited = 'parent';
                    if (isVacant) {

                        if (property['summary']['propclass']) {
                            if (property['summary']['propclass'].toLowerCase() === "vacant") {
                                
                                location.push([property["location"]['latitude'], property["location"]['longitude'], property['address']['oneLine'],property["address"]["line1"],property["address"]["line2"]]);
                                locationLatLng.push([property["location"]['latitude'], property["location"]['longitude'], property['address']['oneLine'],property["address"]["line1"],property["address"]["line2"]]);
                            }
                        }

                    } else {
                        const pattern = /l\s*([0-9]*).-.([0-9]*)/gi;
                        const patt1 = /lot\s*.([0-9]*).&.([0-9]*)/gi;
                        const patt10 = /lots\s*.([0-9]*).&.([0-9]*)/gi;
                        const patt2 = /lots\s*.([0-9]*).([0-9]*).&.([0-9]*)/gi;
                        const patt3 = /lts\s*.([0-9]*).([0-9]*).&.([0-9]*)/gi;
                        const patt4 = /lts\s*.([0-9]*).([0-9]*).([&]*).([0-9]*)/gi;
                        const patt5 = /lts\s*.([0-9]*).([0-9]*).(&[0-9]*)/gi;
                        const patt6 = /lts\s*.([0-9]*).([0-9]*).&.([0-9]*)/gi;
                        const patt7 = /l\s*([0-9]*).-.([0-9]*)/gi;
                        const patt8 = /lot\s*([0-9]*).-.([0-9]*)/gi;
                        const patt9 = /lot\s*([0-9]*).-.([0-9]*)/gi;
                        const patt11 = /lts\s*([0-9]*).thru.([0-9]*)/gi;

                        const patt12 = /l\s*([0-9]*).thru.([0-9]*)/gi;
                        const patt13 = /lt\s*([0-9]*).thru.([0-9]*)/gi;
                        const patt14 = /lots\s*([0-9]*).thru.([0-9]*)/gi;
                        const patt15 = /lots\s*([0-9]*).thru.([0-9]*)/gi;
                        const patt16 = /lts\s*([0-9]*).thru.([0-9]*)/gi;

                        const patt17 = /lot\s*([0-9]*).&\s*PT\s*LOT.([0-9]*)/gi;
                        const patt18 = /lt\s*([0-9]*).&\s*pt.([0-9]*)/gi;
                        const patt19 = /lts\s*([0-9]*).&\s*pt.([0-9]*)/gi;
                        const patt20 = /lot\s*([0-9]*).&\s*.([0-9]*)/gi;
                        const patt21 = /lots\s*([0-9]*).and\s*lot\s*.([0-9]*)/gi;
                        const patt22 = /lots\s*([0-9]*).and\s*lots\s*.([0-9]*)/gi;
                        const patt23 = /lot\s*([0-9]*).and\s*lot\s*.([0-9]*)/gi;
                        const patt24 = /lot\s*([0-9]*).and\s*lots\s*.([0-9]*)/gi;

                        if (property['summary']['legal1']) {

                            var result = property['summary']['legal1'].match(pattern);
                            var result2 = property['summary']['legal1'].match(patt1);
                            var result3 = property['summary']['legal1'].match(patt2);
                            var result4 = property['summary']['legal1'].match(patt3);
                            var result5 = property['summary']['legal1'].match(patt4);
                            var result6 = property['summary']['legal1'].match(patt5);
                            var result7 = property['summary']['legal1'].match(patt6);
                            var result8 = property['summary']['legal1'].match(patt7);
                            var result9 = property['summary']['legal1'].match(patt8);
                            var result10 = property['summary']['legal1'].match(patt9);
                            var result11 = property['summary']['legal1'].match(patt10);
                            var result12 = property['summary']['legal1'].match(patt11);

                            var result13 = property['summary']['legal1'].match(patt12);
                            var result14 = property['summary']['legal1'].match(patt13);
                            var result15 = property['summary']['legal1'].match(patt14);
                            var result16 = property['summary']['legal1'].match(patt15);
                            var result17 = property['summary']['legal1'].match(patt16);
                            var result18 = property['summary']['legal1'].match(patt17);
                            var result19 = property['summary']['legal1'].match(patt18);
                            var result20 = property['summary']['legal1'].match(patt19);
                            var result21 = property['summary']['legal1'].match(patt20);
                            var result22 = property['summary']['legal1'].match(patt21);
                            var result23 = property['summary']['legal1'].match(patt22);
                            var result24 = property['summary']['legal1'].match(patt23);
                            var result25 = property['summary']['legal1'].match(patt24);


                            if (result || result2 || result3 || result4 || result5 || result6 || result7 || result8 || result9 || result10 || result11 || result12 || result13 || result14 || result15 || result16 || result17 || result18 || result19 || result20 || result21 || result22 || result23 || result24 || result25) {

                                location.push([property["location"]['latitude'], property["location"]['longitude'], property['address']['oneLine'],property["address"]["line1"],property["address"]["line2"]]);
                                locationLatLng.push([property["location"]['latitude'], property["location"]['longitude'], property['address']['oneLine'],property["address"]["line1"],property["address"]["line2"]]);
                            } 

                        }
                    }
                }
            }

            if (document.URL.includes('hpl2')) {

                clusterize.update(validPropertyList);
                if (Math.floor(totalPages) == data.status.page) {
                    clusterize.refresh();
                }
            } else {
                if (totalPages == data.status.page) {

                }
                // console.log(locationLatLng.length);
                f(location);

            }

        })
}

function f(locations) {
    console.log(locations.length);

    loc = new google.maps.LatLng("{{$lat}}" ,"{{$longi}}");
    
    var infowindow = new google.maps.InfoWindow();
    for (let i = 0; i < locations.length; i++) {

        var markers = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][0], locations[i][1]),
            animation: google.maps.Animation.DROP,
            map: map,
            icon: '/Img/icons/pin_b.png'
        });

        google.maps.event.addListener(markers, 'click', (function (markers, i) {
            return function () {
                infowindow.setContent(locations[i][2]);
                // infowindow.open(map, markers);
                modalOpen(locations[i]);
                
            }
        })(markers, i))
    }

    map.setOptions({styles: styles['hide']});
}

function modalOpen(locationData){
    
        const lat = locationData[0];
        const long = locationData[1];
        const line1 = locationData[3];
        const line2 = locationData[4];
        const oneline = locationData[2];
        console.log(oneline);
        $("#DetailHref").attr("href","/getOwnerDetail/"+line1+"/"+line2);

        $('#ModalImg').attr("src", "https://maps.googleapis.com/maps/api/streetview?size=800x400&location=" + (oneline) + "&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74");
      

        const pro_myOptions = {
            zoom: 19,
            center: new google.maps.LatLng(lat, long),
            scrollwheel:true
        };
        const newModalmap = new google.maps.Map(document.getElementById("newModalmap"), pro_myOptions);

        geocoder = new google.maps.Geocoder();
        geocoder.geocode( { 'address': oneline}, function(results, status) {
            if (status == 'OK') {
                console.log(results[0].geometry.location.lat(),results[0].geometry.location.lng());
                
                newModalmap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: newModalmap,
                    position: results[0].geometry.location,
                    animation: google.maps.Animation.DROP
                });
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });

        newModalmap.setOptions({styles: styles['hide']});

        $('#MarkerModal').modal('show')
}
</script>
@endsection
