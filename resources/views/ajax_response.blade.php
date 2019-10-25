
        @if($AVMResult["property"])
            @foreach ($AVMResult["property"] as $key => $WholePropertydata)
                <div class="card">
                    <div class="card-header">
                        <a  href="/propertymail/{{$fullname}}/{{$fulladdress}}/abc@email.com/{{\App\Mailertemplate::first()->id}}" target="_blank"  class="btn btn-dark" style="float: right;" id='MailerBtn'>E-Mail Property Owner</a>
                        <h4>{{$WholePropertydata["address"]["line1"]}}</h4>
                        <h7>{{$WholePropertydata["address"]["line2"]}}</h7>
                    </div>
                    <div>
                        <!-- <div class="row">
                            <div class="col-6">
                                <img width="100%" height="400" src="https://maps.googleapis.com/maps/api/streetview?size=800x400&location={{$fulladdress}}&pitch=0&fov=120&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">
                            </div>
                            <div class="col-6">
                                <div class=" map" id="Mymap" style="width: 100%;height: 100%">

                                </div>
                            </div>
                        </div> -->
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
                            <div class="col-12">
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

<script type="text/javascript">
//  init();

// var map;
// function init() {

    
//     const myOptions = {
//         enableHighAccuracy: true,
//         zoom: 13,
//         center: new google.maps.LatLng( "{{$lat}}" ,"{{$longi}}"),
//         scrollwheel:true
//     };
//      map = new google.maps.Map(document.getElementById("Mymap"), myOptions);

//     const marker = new google.maps.Marker({
//         position: new google.maps.LatLng("{{$lat}}" ,"{{$longi}}"),
//         map: map,
//         animation: google.maps.Animation.DROP
//     });
// }   

// f(locationLatLng);
// function f(locations) {
//     console.log(locations.length);
//     var infowindow = new google.maps.InfoWindow();
//     for (let i = 0; i < locations.length; i++) {
//         var markers = new google.maps.Marker({
//             position: new google.maps.LatLng(locations[i][0], locations[i][1]),
//             animation: google.maps.Animation.DROP,
//             map: map,
//             icon: '/Img/icons/pin_b.png'
//         });
//         google.maps.event.addListener(markers, 'click', (function (markers, i) {
//             return function () {
//                 infowindow.setContent(locations[i][2]);
//                 infowindow.open(map, markers);
//                /* $.ajax({
//                     url:"/get/AjaxResponse/"+locations[i][3]+"/"+locations[i][4],
//                     type:"get",
//                     success:function(resp){
//                         $("#proResponse").empty();
//                         if(resp){
//                             $("#proResponse").append(resp);
//                         }
                       
//                     },
//                     error:function(xhr){
//                         console.log(xhr);
//                     }
//                 });*/
                
//             }
//         })(markers, i))
//     }


// }
</script>



