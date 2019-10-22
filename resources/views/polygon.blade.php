@extends('master')
@section('title','Detail Information')
@section('content')
<div class="card">
	<div class="col-6">
        <div class="map" id="map"></div>

        </div>
    </div>
@endsection

@section('script')
<script>

initMapPoly();
var polys;
function parsePolyStrings(ps) {
    var i, j, lat, lng, tmp, tmpArr,
        arr = [],
        //match '(' and ')' plus contents between them which contain anything other than '(' or ')'
        m = ps.match(/\([^\(\)]+\)/g);
    if (m !== null) {
        for (i = 0; i < m.length; i++) {
            //match all numeric strings
            tmp = m[i].match(/-?\d+\.?\d*/g);
            if (tmp !== null) {
                //convert all the coordinate sets in tmp from strings to Numbers and convert to LatLng objects
                for (j = 0, tmpArr = []; j < tmp.length; j += 2) {
                    lng = Number(tmp[j]);
                    lat = Number(tmp[j + 1]);
                    tmpArr.push(new google.maps.LatLng(lat, lng));
                }
                arr.push(tmpArr);
            }
        }
    }
    //array of arrays of LatLng objects, or empty array
    return arr;
}
var map;
var elevator;
var polygonArray = [];



function initMapPoly(){


  var myOptions = {
      zoom: 9,
      center: new google.maps.LatLng(29.5211712,-95.31132259999998),
      scrollwheel : true,
  };

  map = new google.maps.Map(document.getElementById("map"), myOptions);

  marker;
    //console.log(locations);

    marker = new google.maps.Marker({
        position: new google.maps.LatLng(29.5211712,-95.31132259999998),
        map: map,
        animation: google.maps.Animation.DROP,

    });
     
    $.ajax({
        url:"/jsonData",
        type:"get",
        success:function(resp){
          polys = [resp];
          console.log(polys.length);
          for (i = 0; i < polys.length; i++) {
            tmp = parsePolyStrings(polys[i]);
            if (tmp.length) {
                polys[i] = new google.maps.Polygon({
                    paths: tmp,
                    strokeColor: '#0051FF',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#0051FF',
                    fillOpacity: 0.20
                });
                polys[i].setMap(map);
            }
        }
        },
        error:function(xhr){
          console.log(xhr);
        }
      }); 

    
}
</script>
@endsection