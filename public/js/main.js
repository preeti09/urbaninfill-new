document.cookie = 'cross-site-cookie=bar; SameSite=None; Secure';
var bar1;
var locationLatLng = [];

$("#houseDiv").hide();
$("#eduDiv").hide();
$("#incomeDiv").hide();
$("#poiContent").hide();
$(document).on("click", '.saveBtn', function (event) {

    const line1 = $(this).attr("line1");
    const line2 = $(this).attr("line2");
    saveProperty(line1, line2);
})

function saveProperty(line1, line2) {
    // console.log(line1,line2);
    fetch(buildUrl('/savedata', {line1: line1, line2: line2}), {
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
            return response.text()
        }
        throw new Error(response.statusText)
    })
    .then(function (data) {
        if (data.toString() === "Error") {
            $.notify(data, 'error');
        } else {
            $.notify(data, 'success');
        }
        console.log(data);


    });
}

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

$(document).ready(function () {
    let gline1;
    let gline2
    $(document).on("click", ".hotlineLabel", function (event) {
        const line1 = $(this).attr("line1");
        const line2 = $(this).attr("line2");
        const oneline = $(this).attr("oneline");
        // const oneline =line1+","+line2;
        console.log(oneline);
        gline1 = line1;
        gline2 = line2;
        check_visited_links(line1 + line2);
        this.className += ' parent';
        const lat = $(this).attr("lat");
        const long = $(this).attr("long");
        $("#DetailHref").attr("href","/getOwnerDetail/"+line1+"/"+line2);
        // $("#DetailPro").attr("href","/getOwnerDetail/"+line1+"/"+line2);
        $('#ModalImg').attr("src", "https://maps.googleapis.com/maps/api/streetview?size=800x400&location=" + (oneline) + "&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74");
        //$('#SaveLink').attr("href","https://maps.googleapis.com/maps/api/streetview?size=800x400&location=" +lat+ "," +long + "&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74");

        const pro_myOptions = {
            zoom: 19,
            center: new google.maps.LatLng(lat, long),
            scrollwheel:true
        };
        const Modalmap = new google.maps.Map(document.getElementById("Modalmap"), pro_myOptions);

        //marker;
        
        geocoder.geocode( { 'address': oneline}, function(results, status) {
            if (status == 'OK') {
                console.log(results[0].geometry.location.lat(),results[0].geometry.location.lng());
                
                Modalmap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: Modalmap,
                    position: results[0].geometry.location,
                    // position: new google.maps.LatLng(pro_lat, pro_long),
                    animation: google.maps.Animation.DROP
                });
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });

        Modalmap.setOptions({styles: styles['hide']});

        $('#myModal').modal('show')
    });

    $("#SaveLink").click(function () {
        saveProperty(gline1, gline2)
    })

});


$('.toast').toast('show')
// Ajax Requestsfas
$.ajaxSetup({

    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// Mialer event handler get request to send the email

setTimeout(loadingOFF, 1100)

function loadingOFF() {
    $('#isLoaded').css("display", "none");
    $('#loading').css("display", "block");
    $("#loading").fadeOut("slow", function () {

        $('#loading').css("display", "none");

        $("#isLoaded").css("display", "block");
    });
}

$("#searchbyPerson").click(function (e) {
    e.preventDefault();
    const fName = $("#fName").val();
    const lName = $("#lName").val();
    const zip = $("#zip").val();

    fetch(buildUrl('/personlist', {fName: fName, lName: lName, zip: zip}), {
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
            console.log(data);
            let index = 0;
            $('#personSearchListdiv').empty();
            for (const rest of data.result) {
                var text = '<li class="list-group-item list-group-item-action ">' +
                    '<div class="d-flex w-100 justify-content-between">' +
                    '<h5 class="mb-1">' + rest.name[0].data + '</h5>' +
                    '<a href="/perosndetail/' + encodeURI(rest.name[0].first) + '/' + encodeURI(rest.name[0].last) + '/' + encodeURI(rest.pid) + '" class="btn btn-primary">Detail</a>' +
                    '</div>' +
                    '<hr>' +
                    '<div class="d-flex w-100 justify-content-between">';
                for (const phone of rest.phone) {
                    text = text + '  <p>Phone #' + phone.number + '</p>';
                }
                if (rest.dob.length > 0) {
                    text = text + '</div>' +
                        ' <hr>' +
                        '<p class="mb-1">DoB ' + rest.dob[0].date.data + '</p>';
                }
                text = text + '<div class="d-flex w-100 justify-content-between">';

                for (const email of rest.email) {
                    text = text + '<p class="mb-1">' + email.data + '</p>'
                }
                text = text + '</div>' +
                    ' </li>';
                $("#personSearchListdiv").append(text);
                index++;
            }
        });

});

function isEmptyOrSpaces(str) {
    return str === null || str.match(/^ *$/) !== null || str === "";
}

$("#search").on('keypress', function (e) {
    if (e.which == 13) {
        ipage = 1;
        $('.swiper-wrapper').empty();
        const address = $("#search").val();
        if (!isEmptyOrSpaces(address))
            codeAddress(address);
        else
            alert("Please enter the zip code")
    }
});

$("#searchAddress").on('keypress', function (e) {
    if (e.which == 13) {
        $("#searchByAddress").trigger("click");
    }
});

$("#Vacantsearch").on('keypress', function (e) {
    if (e.which == 13) {
        $("#searchByPropertyVacant").trigger("click");
    }
});
$("#searchByProperty").click(function (e) {
    e.preventDefault();
    ipage = 1;
    $('.swiper-wrapper').empty();
    const address = $("#search").val();
    if (!isEmptyOrSpaces(address)) {
        bar1 = new ldBar("#ldBar");
        bar1.set(0);
        count_request_completed = 0;
        $('#issearchdone').css("display", "none");
        $('#searchloading').css("display", "block");

        codeAddress(address);
        if (document.URL.includes('hpl2')) {
            clusterize = new Clusterize({
                scrollId: 'scrollArea',
                contentId: 'contentArea'
            });
        } else {
            swiper = new Swiper('.swiper-container', {
                slidesPerView: 5,
                direction: 'vertical',
                slideToClickedSlide: true,
                preventClicks: true, observer: true, observeParents: true,
                on: {
                    click: function (swiper, e) {
                        // var clicked = $(e.target);
                        focusonmarker(this.activeIndex);
                        //console.log(clicked);
                    },
                    slideChangeTransitionEnd: function (swiper) {
                        focusonmarker(this.activeIndex);

                    }
                },
                navigation: {
                    nextEl: '#prev-slide',
                    prevEl: '#next-slide',
                }, scrollbar: {
                    el: '.swiper-scrollbar',
                },
                mousewheel: {
                    invert: false,
                },
            });
        }
    } else
        alert("Please enter the zip code")
});


$("#searchByPropertyVacant").click(function (e) {
    e.preventDefault();
    ipage = 1;


    $('.swiper-wrapper').empty();
    const address = $("#Vacantsearch").val();
    if (!isEmptyOrSpaces(address)) {
        bar1 = new ldBar("#ldBar");
        bar1.set(0);
        count_request_completed = 0;
        $('#issearchdone').css("display", "none");
        $('#searchloading').css("display", "block");
        codeAddress(address, true);
        swiper = new Swiper('.swiper-container', {
            slidesPerView: 5,
            direction: 'vertical',
            slideToClickedSlide: true,
            preventClicks: true,
            observer: true, observeParents: true,
            on: {
                click: function (swiper, e) {
                    // var clicked = $(e.target);
                    focusonmarker(this.activeIndex);
                    //console.log(clicked);
                },
                slideChangeTransitionEnd: function (swiper) {
                    focusonmarker(this.activeIndex);

                }
            },
            navigation: {
                nextEl: '#prev-slide',
                prevEl: '#next-slide',
            }, scrollbar: {
                el: '.swiper-scrollbar',
            },
            mousewheel: {
                invert: false,
            },
        });
    } else
        alert("Please enter the zip code")

});
$("#searchByTypeProperty").click(function (e) {
    e.preventDefault();
    ipage = 1;


    $('.swiper-wrapper').empty();
    const address = $("#Typesearch").val();
    const type = $("#typeSelect").val();
    if (!(isEmptyOrSpaces(address) || isEmptyOrSpaces(type) ) ) {
        bar1 = new ldBar("#ldBar");
        bar1.set(0);
        count_request_completed = 0;
        $('#issearchdone').css("display", "none");
        $('#searchloading').css("display", "block");
        codeAddress(address, true,type);
        swiper = new Swiper('.swiper-container', {
            slidesPerView: 5,
            direction: 'vertical',
            slideToClickedSlide: true,
            preventClicks: true, observer: true, observeParents: true,
            on: {
                click: function (swiper, e) {
                    // var clicked = $(e.target);
                    focusonmarker(this.activeIndex);
                    //console.log(clicked);
                },
                slideChangeTransitionEnd: function (swiper) {
                    focusonmarker(this.activeIndex);

                }
            },
            navigation: {
                nextEl: '#prev-slide',
                prevEl: '#next-slide',
            }, scrollbar: {
                el: '.swiper-scrollbar',
            },
            mousewheel: {
                invert: false,
            },
        });
    } else
        alert("Please enter the zip code")

});
var detailViews;
var clusterize;
$('#searchByAddress').click(function (e) {
    e.preventDefault();

    $('#issearchdone').css("display", "none");
    $('#searchloading').css("display", "block");
    const address = $("#searchAddress").val();
    if (isEmptyOrSpaces(address)) {
        alert("Please enter the address code")
        return
    }

    fetch("/getPropertyResponse", {
        method: "post", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        body: JSON.stringify({address: address}),
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
            throw new Error(response.status)
        })
        .then(function (data) {
            console.log(data);
            detailViews = data["detailViews"];
            $('#LegalAddress').text(data["legaladdress"]);

            //$('#view').html(data["view"]);
            const link = '/getOwnerDetail/' + encodeURI(data["line1"]) + '/' + encodeURI(data["line2"]);
            $('#detailviewBTN').show();
            $('#detailviewBTN').attr('href', link);
            f1();
            initMap(data["final_array"], data["lat"], data["lng"])

            $("#searchloading").fadeOut("slow", function () {

                $('#searchloading').css("display", "none");

                $("#issearchdone").css("display", "block");
            });
        }).catch(error => {
        if (error.message === '500')
            $.notify("You enter the wrong address", 'error');
        else
            $.notify('You exceed the Address search limit', 'error');

    });
    /*
$.ajax({
    type:'post',
    url:'/getPropertyResponse',
    data:{address:address},
    success:function(data){
        console.log(data);
        $('#LegalAddress').text(data["legaladdress"]);
        $('#view').html(data["view"]);
    },
    timeout: 5000
});*/
});
let g_BoolFor_HistoricSearch = true;
let g_BoolFor_VacantSearch = true;
var totalPages = 10;

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
                // console.log(data);
                searchCount = 0;
                $("#poiContent").hide();
                for (let i = 1; i <= totalPages; i++) {
                    postData('/allpropertiesList', {
                        lat: lat,
                        lng: lng,
                        page: i,
                        zip: postalcode
                    }, isVacant);
                }
                if(totalPages == 0 )
                {
                    $("#searchloading").fadeOut("slow", function () {

                        $('#searchloading').css("display", "none");

                        $("#issearchdone").css("display", "block");
                    });
                    $('#searchCount').text("Property count: 0 (No result)" );
                    $("#poiContent").show();
                }

                init();
            }
        });

    /*  $.ajax({
          type:'get',
          url:'/getTotalPages',
          data:{lat:lat,lng:lng,zip:postalcode},
          cache:false,
          success:function(data){
              totalPages = data;
              console.log(data);

          },
          complete:function()
          {
              for (let i = 1; i <= totalPages; i++) {
                  console.log(postData('/allpropertiesList', {lat: lat, lng: lng, page: i, zip: postalcode},isVacant));
              }

          },
          timeout: 5000
      });*/
}

function getTypelist(postalcode, type) {


    fetch(buildUrl('/getTotalTypePages', {postalcode: postalcode, type: type}), {
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
                console.log(totalPages);
                searchCount = 0;
                $("#poiContent").hide();
                for (let i = 1; i <= totalPages; i++) {
                    console.log(postTypeData('/getpropertiesByTypeList', {
                        postalcode: postalcode,
                        type: type,
                        page: i
                    }, ));
                }
                if(totalPages == 0 )
                {
                    $("#searchloading").fadeOut("slow", function () {

                        $('#searchloading').css("display", "none");

                        $("#issearchdone").css("display", "block");
                    });
                    $('#searchCount').text("Property count: 0 (No result)" );
                    $("#poiContent").show();
                }
            }
        });
}
function postTypeData(url = ``, data = {}) {
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
            console.log(data);

            count_request_completed++;
            let validPropertyList = [];
            let location = [];
            if (data) {
                bar1.set((count_request_completed / parseInt(totalPages)) * 100);
                if (count_request_completed == totalPages) {
                    $("#searchloading").fadeOut("slow", function () {

                        $('#searchloading').css("display", "none");

                        $("#issearchdone").css("display", "block");
                    });
                }
                if (typeof (data) === typeof ('d'))
                    return;
                for (const [i, property] of data.property.entries()) {
                    if (property["address"]["postal1"] != postalcode)
                        continue;
                    let visited = '';
                    const address = encodeURI(property["address"]["line1"]) + encodeURI(property["address"]["line2"]);
                    if (b_check_visited_links(address.replace( '#', "")))
                        visited = 'parent';

                    searchCount++;

                    $('#searchCount').text("Property count : " + searchCount);
                    $("#poiContent").show();

                    var text = '<div class="swiper-slide" style="height: 180px;">' +
                        '<div class="box selectPOI">' +
                        '<span class="h3 hotlineLabel ' + visited + '" target="_blank" lat ="' + property["location"]["latitude"] + '" long = "' + property["location"]["longitude"] + '" line1 = "' + encodeURI(property["address"]["line1"]).replace( '#', "") + '" line2="' + encodeURI(property["address"]["line2"]).replace( '#', "") + '"oneLine="' + (property["address"]["oneLine"]).replace( '#', "") + '" > Hot Property ('+property["address"]["oneLine"]+')</span>' +
                        '<div class="float-right">' +
                        '<input type="checkbox" name="selectedItem" class="selectedProperty" aria-label="Checkbox for following text input">' +
                        //'<a target="_blank" href="/getOwnerDetail/'+encodeURI(property["address"]["line1"])+'/' +encodeURI(property["address"]["line2"])+'"style="padding: 5px;"><i class="fas fa-home" style="color: black;"></i></a>'+
                        '<button type="button" class="saveBtn btn btn-link"  line1 = "' + encodeURI(property["address"]["line1"]).replace( '#', "") + '" line2="' + encodeURI(property["address"]["line2"]).replace( '#', "") + '" style="padding: 5px;"><i class="fas fa-save" style="color: black;"></i></button>' +
                        '</div>' +
                        '<div class="float-left">' +
                        '<img width="250px" src="https://maps.googleapis.com/maps/api/streetview?size=100x100&location=' + property["address"]["oneLine"].replace( '#', "")  + '&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">' +
                        '</div></div></div>';

                    swiper.appendSlide(text);

                    location.push([property["location"]['latitude'], property["location"]['longitude'], property['address']['oneLine'].replace( '#', "")]);

                }
                f(location);
            }
        })
}


function buildUrl(url, parameters, isVacant) {
    let qs = "";
    for (const key in parameters) {
        if (parameters.hasOwnProperty(key)) {
            const value = parameters[key];
            qs +=
                encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";
        }
    }
    if (qs.length > 0) {
        qs = qs.substring(0, qs.length - 1); //chop off last "&"
        url = url + "?" + qs;
    }

    return url;
}

let searchCount = 0
let requestCompleted = 0;
let count_request_completed = 0

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
            
            count_request_completed++;
            let validPropertyList = [];
            let location = [];
            // totalPages = data.property.length;
            if (data) {
                bar1.set((count_request_completed / parseInt(totalPages)) * 100);
                if (count_request_completed == totalPages) {
                    $("#searchloading").fadeOut("slow", function () {

                        $('#searchloading').css("display", "none");

                        $("#issearchdone").css("display", "block");
                    });
                }


                if (typeof (data) === typeof ('d'))
                    return;
                for (const [i, property] of data.property.entries()) {

                    $("#clear_polygon").show();
                    if (property["address"]["postal1"] != postalcode)
                        continue;
                    let visited = '';
                    const address = encodeURI(property["address"]["line1"]) + encodeURI(property["address"]["line2"]);
                    if (b_check_visited_links(address))
                        visited = 'parent';
                    if (isVacant) {

                        if (property['summary']['propclass']) {
                            if (property['summary']['propclass'].toLowerCase() === "vacant") {
                                searchCount++;
                                $('#searchCount').text("Property count : " + searchCount);
                                $("#poiContent").show();
                                var text = '<div class="swiper-slide" id="'+property["location"]["latitude"]+','+property["location"]["longitude"]+'" style="height: 100px;">' +
                                    '<div class="box selectPOI">' +
                                    '<span class="h3 hotlineLabel ' + visited + '" target="_blank" lat ="' + property["location"]["latitude"] + '" long = "' + property["location"]["longitude"] + '" line1 = "' + encodeURI(property["address"]["line1"]).replace( '#', "") + '" line2="' + encodeURI(property["address"]["line2"]).replace( '#', "") + '"oneline="' + (property["address"]["oneLine"]).replace( '#', "") + '" > Hot Property </span>' +
                                    '<div class="float-right">' +
                                    '<input type="checkbox" name="selectedItem" class="selectedProperty" aria-label="Checkbox for following text input">' +
                                    '<button type="button" class="saveBtn btn btn-link"  line1 = "' + encodeURI(property["address"]["line1"]).replace( '#', "") + '" line2="' + encodeURI(property["address"]["line2"]).replace( '#', "") + '" style="padding: 5px;"><i class="fas fa-save" style="color: black;"></i></button>' +
                                    '</div>' +
                                    '<div class="float-left">' +
                                    '<img width="250px" src="https://maps.googleapis.com/maps/api/streetview?size=100x100&location=' + property["address"]["oneLine"].replace( '#', "") + '&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">' +
                                    '</div></div></div>';
                                swiper.appendSlide(text);
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
                                searchCount++;
                                $('#searchCount').text("Property count : " + searchCount);
                                $("#poiContent").show();

                                var text = '<div class="swiper-slide" id="'+property["location"]["latitude"]+','+property["location"]["longitude"]+'" style="height: 100px;">' +
                                    '<div class="box selectPOI">' +
                                    '<span class="h3 hotlineLabel ' + visited + '" target="_blank" lat ="' + property["location"]["latitude"] + '" long = "' + property["location"]["longitude"] + '" line1 = "' + encodeURI(property["address"]["line1"]).replace( '#', "") + '" line2="' + encodeURI(property["address"]["line2"]).replace( '#', "") + '"oneline="' + (property["address"]["oneLine"]).replace( '#', "") + '" > Hot Property </span>' +
                                    '<div class="float-right">' +
                                    '<input type="checkbox" name="selectedItem" class="selectedProperty" aria-label="Checkbox for following text input">' +
                                    //'<a target="_blank" href="/getOwnerDetail/'+encodeURI(property["address"]["line1"])+'/' +encodeURI(property["address"]["line2"])+'"style="padding: 5px;"><i class="fas fa-home" style="color: black;"></i></a>'+
                                    '<button type="button" class="saveBtn btn btn-link"  line1 = "' + encodeURI(property["address"]["line1"]).replace( '#', "") + '" line2="' + encodeURI(property["address"]["line2"]).replace( '#', "") + '" style="padding: 5px;"><i class="fas fa-save" style="color: black;"></i></button>' +
                                    '</div>' +
                                    '<div class="float-left">' +
                                    '<img width="250px" src="https://maps.googleapis.com/maps/api/streetview?size=250x250&location=' + property["address"]["oneLine"].replace( '#', "")  + '&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">' +
                                    '</div></div></div>';
                                //$(".swiper-wrapper").append(text);
                                swiper.appendSlide(text);
                                validPropertyList.push(text);
                                location.push([property["location"]['latitude'], property["location"]['longitude'], property['address']['oneLine'],property["address"]["line1"],property["address"]["line2"]]);
                                locationLatLng.push([property["location"]['latitude'], property["location"]['longitude'], property['address']['oneLine'],property["address"]["line1"],property["address"]["line2"]]);
                            } /*else if (result2) {
                                var text = '<div class="swiper-slide" ajaxlink= "/getOwnerDetail/'+property["address"]["line1"]+'/' +property["address"]["line2"]+'"\>' +
                                    '<div class="box selectPOI" id="5">' +
                                    '<h1>' + property['address']['oneLine'] + '</h1>' +
                                    '<div class="restaurant-content">' +
                                    '<label>Legal Description</label>' +
                                    '<small>' + property['summary']['legal1'] + '</small></div></div></div>';
                                $(".swiper-wrapper").append(text);
                            } else if (result3) {
                                var text = '<div class="swiper-slide" ajaxlink= "/getOwnerDetail/'+property["address"]["line1"]+'/' +property["address"]["line2"]+'"\>'  +
                                    '<div class="box selectPOI" id="5">' +
                                    '<h1>' + property['address']['oneLine'] + '</h1>' +
                                    '<div class="restaurant-content">' +
                                    '<label>Legal Description</label>' +
                                    '<small>' + property['summary']['legal1'] + '</small></div></div></div>';
                                $(".swiper-wrapper").append(text);
                            } else if (result4) {
                                var text = '<div class="swiper-slide" ajaxlink= "/getOwnerDetail/'+property["address"]["line1"]+'/' +property["address"]["line2"]+'"\>'  +
                                    '<div class="box selectPOI" id="5">' +
                                    '<h1>' + property['address']['oneLine'] + '</h1>' +
                                    '<div class="restaurant-content">' +
                                    '<label>Legal Description</label>' +
                                    '<small>' + property['summary']['legal1'] + '</small></div></div></div>';
                                $(".swiper-wrapper").append(text);
                            } else if (result5) {
                                var text = '<div class="swiper-slide" ajaxlink= "/getOwnerDetail/'+property["address"]["line1"]+'/' +property["address"]["line2"]+'"\>'  +
                                    '<div class="box selectPOI" id="5">' +
                                    '<h1>' + property['address']['oneLine'] + '</h1>' +
                                    '<div class="restaurant-content">' +
                                    '<label>Legal Description</label>' +
                                    '<small>' + property['summary']['legal1'] + '</small></div></div></div>';
                                $(".swiper-wrapper").append(text);
                            } else if (result6) {
                                var text = '<div class="swiper-slide" ajaxlink= "/getOwnerDetail/'+property["address"]["line1"]+'/' +property["address"]["line2"]+'"\>'  +
                                    '<div class="box selectPOI" id="5">' +
                                    '<h1>' + property['address']['oneLine'] + '</h1>' +
                                    '<div class="restaurant-content">' +
                                    '<label>Legal Description</label>' +
                                    '<small>' + property['summary']['legal1'] + '</small></div></div></div>';
                                $(".swiper-wrapper").append(text);
                            } else if (result7) {
                                var text = '<div class="swiper-slide" ajaxlink= "/getOwnerDetail/'+property["address"]["line1"]+'/' +property["address"]["line2"]+'"\>'  +
                                    '<div class="box selectPOI" id="5">' +
                                    '<h1>' + property['address']['oneLine'] + '</h1>' +
                                    '<div class="restaurant-content">' +
                                    '<label>Legal Description</label>' +
                                    '<small>' + property['summary']['legal1'] + '</small></div></div></div>';
                                $(".swiper-wrapper").append(text);
                            }*/

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

function check_visited_links(link) {
    let visited_links = JSON.parse(sessionStorage.getItem('visited_links')) || [];

    if (visited_links.indexOf(link) === -1) {
        visited_links.push(link);
        sessionStorage.setItem('visited_links', JSON.stringify(visited_links));
    }//that.parentNode.className += ' visited';
}

function b_check_visited_links(link) {
    let visited_links = JSON.parse(sessionStorage.getItem('visited_links')) || [];

    if (visited_links.indexOf(link) === -1) {
        return false;
    }//that.parentNode.className += ' visited';
    return true;

}

var ipage = 1;

function getpageData(lat, lng, totalpage) {
    // console.log(postalcode);
    $.ajax({
        type: 'get',
        async: false,
        url: '/allpropertiesList',
        data: {lat: lat, lng: lng, page: ipage, zip: postalcode},
        success: function (data) {
            if (data) {
                for (const property of data.property) {
                    const pattern = /l.([0-9]*)-([0-9]*)/gi;
                    const patt1 = /lot.([0-9]*)&([0-9]*)/gi;
                    if (property['summary']['legal1']) {
                        var result = property['summary']['legal1'].match(pattern);
                        var result2 = property['summary']['legal1'].match(patt1);
                        if (result) {
                            var text = '<div class="swiper-slide">' +
                                '<div class="box selectPOI" id="5">' +
                                '<h1>' + property['address']['oneLine'] + '</h1>' +
                                '<div class="restaurant-content">' +
                                '<label>Legal Description</label>' +
                                '<small>' + property['summary']['legal1'] + '</small></div></div></div>';
                            $(".swiper-wrapper").append(text);
                        } else if (result2) {
                            var text = '<div class="swiper-slide">' +
                                '<div class="box selectPOI" id="5">' +
                                '<h1>' + property['address']['oneLine'] + '</h1>' +
                                '<div class="restaurant-content">' +
                                '<label>Legal Description</label>' +
                                '<small>' + property['summary']['legal1'] + '</small></div></div></div>';
                            $(".swiper-wrapper").append(text);
                        }
                    }
                }
            }
            //console.log(data);

        },
        complete: function () {
            ipage++;
            if (ipage <= totalpage) {
                getpageData(lat, lng, totalpage)
            }
        },
        timeout: 5000
    });

}

$(document).on('click','#SavePro',function(){
    var line1 = $(this).attr("line1");
    var line2 = $(this).attr("line2");
    saveProperty(line1, line2);
});

var homemarkers = [];
var swiper;
var markers;
var infowindow;
var bounds;
var loc;
function f(locations) {
    // swiper.init();
    if(locations.length >= 1){
        lat = locations[1][0];
        lng= locations[1][1];
        loc = new google.maps.LatLng(lat, lng);
    }
    
    bounds = new google.maps.LatLngBounds();
    
    bounds.extend(loc);
    swiper.slideTo(swiper.initialSlide);
    swiper.update();
    for (let i = 0; i < locations.length; i++) {
        
        var markers = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][0], locations[i][1]),
            animation: google.maps.Animation.DROP,
            map: map,
            icon: 'Img/icons/pin_b.png'
        });
        
        google.maps.event.addListener(markers, 'click', (function (markers, i) {
            var html = "<a style='color: #007bff;cursor: pointer;font-weight: bold;font-size: 14px;' line1='"+locations[i][3]+"' line2='"+locations[i][4]+"' id='SavePro'>Save Property</a>";

            var html2 = "<br><a href='/getOwnerDetail/"+encodeURI(locations[i][3])+"/"+encodeURI(locations[i][4])+"' style='color: #007bff;cursor: pointer;font-weight: bold;font-size: 14px;' target='_blank' id='DetailPro'>Property Detail</a>";
            var content = "<p>"+locations[i][2]+"</p>";
            
            return function () {
                
                infowindow.setContent(html);
                infowindow.open(map, markers);
                swiper.slideTo(markers.get("id"));

                swiper.updateSlidesClasses();
                
            }
        })(markers, i))
        markers.set("id", homemarkers.length);
        homemarkers.push(markers);
        
        // focusonmarker(0);
        // $("#searchCount").text("Property Count :" + i);
    }
    // $("#searchCount").text("Property Count :" + locations.length);

    map.panToBounds(bounds);
    map.setOptions({styles: styles['hide']});

    // console.log(homemarkers.length,"homemarkers");
}

function focusonmarker(i) {
    // setMapOnAll(null)
    if (homemarkers.length > 0) {
        homemarkers[i].setMap(map);
        google.maps.event.trigger(homemarkers[i], "click");
    }
}

function setMapOnAll(map) {
    for (var i = 0; i < homemarkers.length; i++) {
        homemarkers[i].setMap(map);
    }
}

function f1() {

    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 30,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        on: {
            click: function (swiper, e) {
                // var clicked = $(e.target);
                openInfoModal(this.clickedIndex + 1);
                $('#detailViews').empty();
                $('#detailViews').html(detailViews[this.clickedIndex]);
                //console.log(clicked);
            },
            slideChange: function () {

                //$(".selectSchool")[this.activeIndex+1].trigger("click");
                openInfoModal(this.activeIndex + 1);
                $('#detailViews').empty();
                $('#detailViews').html(detailViews[this.activeIndex]);

            }
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // Responsive breakpoints
        breakpoints: {
            // when window width is <= 640px
            992: {
                slidesPerView: 1,
                spaceBetween: 30
            }
        }
    });
    $(function () {

        setTimeout(function () {
            $('#body').css("min-height", (parseInt($(window).height()) - 55) + "px");
        }, 500);

        /* $("body").on("click",".swiper-button-next",function(){
            var active1= $('.swiper-slide-active > .box').attr("id");
            var active2= $('.swiper-slide-next > .box').attr("id");
            var active3= $('.swiper-slide-next').next('.swiper-slide').find('.box').attr("id");

            setTimeout(function(){ openInfoModal(parseFloat(active1)-1); },100);
            setTimeout(function(){ openInfoModal(parseFloat(active2)-1); },100);
            setTimeout(function(){ openInfoModal(parseFloat(active3)-1); },100);


        }); */

        $("body").on("click", ".selectSchool", function () {

            var schoolID = $(this).data("school");
            var blockID = $(this).attr("id");
            //Selection code
            $(".whitebg").show();
            $(".greybg").hide();
            $(".selectSchool").removeClass("active");
            $(".colorCode").removeClass("white");
            $(this).find(".whitebg").hide();
            $(this).find(".greybg").show();
            $(this).addClass("active");
            $(this).find('.colorCode').addClass("white");
            //openInfoModal(parseFloat(blockID))
            //Selection code End
            /*$.ajax({
                url: '/school-details?school_id='+schoolID,//AJAX URL WHERE THE LOGIC HAS BUILD
                beforeSend: function() {
                    $('.ajax-loader').show();
                },
                success:function(response) {
                    $('.ajax-loader').hide();
                    $(".schoolDetail").html(response);
                    var focusElement = $(".schoolDetail");
                    ScrollToTop(focusElement, function() { focusElement.focus(); });
                    setTimeout(function(){ openInfoModal(parseFloat(blockID)); },100);
                }
            });*/
        });
    });
    openInfoModal(1);
    $(".selectSchool").first().trigger("click");
    $('#detailViews').empty();
    $('#detailViews').html(detailViews[0]);
}

var marker;
var gmarkers = [];

function initMap(finalarray, lat, lng) {
    var locations = [];
    if (finalarray.length != 0) {
        for (let $publicSchool of finalarray) {
            if ($publicSchool['InstitutionName'] != '') {
                locations.push([
                    $publicSchool['InstitutionName'],
                    $publicSchool['geocodinglatitude'],
                    $publicSchool['geocodinglongitude'],
                    "<div id='iw-container'><div class='iw-title'>" + $publicSchool['InstitutionName'] + "</div><div class='iw-content'><p>" + $publicSchool['school_address']['locationaddress'] + " " + $publicSchool['school_address']['locationcity'] + " " + $publicSchool['school_address']['stateabbrev'] + ", " + $publicSchool['school_address']['ZIP'] + "</p><p class='distance'>" + $publicSchool['distance'] + " Miles</p><br></div></div>"
                ]);


            }
        }
    }

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: new google.maps.LatLng(lat, lng),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel:true
    });

    var infowindow = new google.maps.InfoWindow();

    var i;
    //console.log(locations);

    marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, lng),
        map: map,
        animation: google.maps.Animation.DROP,
        icon: 'Img/4.png'
    });


    gmarkers.push(marker);


    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            animation: google.maps.Animation.DROP,
            icon: 'Img/1.png'
        });

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][3]);
                infowindow.open(map, marker);
                for (var sm = 0; sm < gmarkers.length; sm++) {
                    if (sm != 0) {
                        gmarkers[sm].setIcon("Img/1.png");
                    }
                }
                marker.setIcon("Img/2.png");
            }
        })(marker, i));
        gmarkers.push(marker);
    }
}

function openInfoModal(i) {
    google.maps.event.trigger(gmarkers[i], "click");
}
// Auto complete text field for google map
var lat, lng, postalcode;
var placeSearch, autocomplete;

var componentForm = {
    postal_code: 'short_name'
};

var polygon ;
var drawingManager ;
var selectedShape;
var all_overlays = [];
function clearSelection() {
  if (selectedShape) {
    selectedShape.setEditable(false);
    selectedShape = null;
  }
}

function setSelection(shape) {
  clearSelection();
  selectedShape = shape;
}

function deleteSelectedShape() {
  
  if (selectedShape) {
    selectedShape.setMap(null);
  }
}
function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('search')),
        {types: ['geocode']});
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('Vacantsearch')),
        {types: ['geocode']});
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('searchAddress')),
        {types: ['geocode']});
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('Typesearch')),
        {types: ['geocode']});

    //autocomplete.addListener('place_changed', fillInAddress);

}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}

var geocoder;
var communitydata;

function codeAddress(address, isVacant = false,searchtype = '') {

    postalcode = -1;
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        'address': address
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            //alert(results[0].geometry.location);
            for (let a of results[0].address_components) {
                if (a.types[0] == 'postal_code') {
                    postalcode = parseInt(a.long_name)
                    console.log(parseInt(postalcode));
                }

            }
            lat = results[0].geometry.location.lat();
            lng = results[0].geometry.location.lng()

            var latlng = {lat: parseFloat(lat), lng: parseFloat(lng)};
            geocoder.geocode({'location': latlng}, function (result, status) {
                // console.log(result)
            })

            $.ajax({
                type: 'POST',
                url: '/getzipdata',
                data: {
                    address: address,
                    location: [results[0].geometry.location.lat(), results[0].geometry.location.lng()],
                    zip: postalcode
                },
                success: function (data) {
                    //console.log(data);
                    polys = [data];
                    // init();
                },
                cache: false,
                complete: function () {
                    if(isEmptyOrSpaces(searchtype))
                        getlist(postalcode,lat, lng, isVacant)
                    else
                        getTypelist(postalcode, searchtype)

                    fetch(buildUrl('/getHouseInventry', {lat: lat, lng: lng}), {
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
                            console.log(data);
                            dountChart(data);
                            $("#houseDiv").show();
                            higherEdu(data);
                            $("#eduDiv").show();
                            incomeChart(data);
                            $("#incomeDiv").show();
                        })


                    /* $.ajax({
                         type:'get',
                         url:'/getHouseInventry',
                         data:{lat : lat,lng:lng},
                         cache:false,
                         success:function(data){
                             console.log(data);
                             dountChart(data);
                             $("#houseDiv").show();
                             higherEdu(data);
                             $("#eduDiv").show();
                             incomeChart(data);
                             $("#incomeDiv").show();

                         },
                         timeout: 5000
                     });*/
                },
                timeout: 5000
            });

        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}

//Higher Education
function higherEdu(communityData) {
    $("#noHS").html(Math.round((100 * (communityData['EDULTGR9'] / communityData['EDUTOTALPOP']))) + "%");
    $("#someHS").html(Math.round((100 * (communityData['EDUSHSCH'] / communityData['EDUTOTALPOP']))) + "%");
    $("#hsGrad").html(Math.round((100 * (communityData['EDUHSCH'] / communityData['EDUTOTALPOP']))) + "%");
    $("#someCollege").html(Math.round((100 * (communityData['EDUSCOLL'] / communityData['EDUTOTALPOP']))) + "%");
    $("#associate").html(Math.round((100 * (communityData['EDUASSOC'] / communityData['EDUTOTALPOP']))) + "%");
    $("#bachlor").html(Math.round((100 * (communityData['EDUBACH'] / communityData['EDUTOTALPOP']))) + "%");
    $("#graduate").html(Math.round((100 * (communityData['EDUGRAD'] / communityData['EDUTOTALPOP']))) + "%");

}

//donut Chart
function dountChart($communityData) {
    var mychart = AmCharts.makeChart("chart-1", {
        "type": "pie",
        "theme": "light",
        "dataProvider": [{
            "title": "Rented " + Math.round(100 * ($communityData['DWLRENT'] / $communityData['DWLTOTAL'])),
            "value": Math.round(100 * ($communityData['DWLRENT'] / $communityData['DWLTOTAL'])),
            "color": '#0051FF'
        }, {
            "title": "Owned " + Math.round(100 * ($communityData['DWLOWNED'] / $communityData['DWLTOTAL'])),
            "value": Math.round(100 * ($communityData['DWLOWNED'] / $communityData['DWLTOTAL'])),
            "color": '#43575f'
        }, {
            "title": "Vacant " + Math.round(100 * ($communityData['DWLVACNT'] / $communityData['DWLTOTAL'])),
            "value": Math.round(100 * ($communityData['DWLVACNT'] / $communityData['DWLTOTAL'])),
            "color": '#d6d6d6'
        }],
        "titleField": "title",
        "valueField": "value",
        "colorField": "color",
        "labelRadius": 0,

        "radius": "42%",
        "innerRadius": "60%",
        "labelText": "[[title]]",
        "export": {
            "enabled": true
        }
    });

}

// income chart
function incomeChart($communityData) {

    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',
        // The data for our dataset
        data: {
            labels: ["<10K", "10K-15K", "15K-20K", "20K-25K", "25K-30K", "30K-35K", "35K-40K", "40K-45K", "45K-50K", "50K-60K", "60K-75K", "75K-100K", "100K-125K", "125K-150K", "150K-200K", "200K-250K", "250K-500K", ">500K"],
            datasets: [{
                label: "",
                //backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(0, 81, 255)',
                data: [$communityData['HINCY00_10'],
                    $communityData['HINCY10_15'],
                    $communityData['HINCY15_20'],
                    $communityData['HINCY20_25'],
                    $communityData['HINCY25_30'],
                    $communityData['HINCY30_35'],
                    $communityData['HINCY35_40'],
                    $communityData['HINCY40_45'],
                    $communityData['HINCY45_50'],
                    $communityData['HINCY50_60'],
                    $communityData['HINCY60_75'],
                    $communityData['HINCY75_100'],
                    $communityData['HINCY100_125'],
                    $communityData['HINCY125_150'],
                    $communityData['HINCY150_200'],
                    $communityData['HINCY200_250'],
                    $communityData['HINCY250_500'],
                    $communityData['HINCYGT_500']],
            }]
        },

        // Configuration options go here
        options: {
            responsive: true,
            legend: {
                display: false
            },
            title: {
                display: false,
                text: 'Chart.js Line Chart'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{

                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Annual Income',
                        fontColor: 'black'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Number of Households',
                        fontColor: 'black'
                    }
                }]
            }
        }
    });


    /*

          $communityData['HINCY00_10'],
           $communityData['HINCY10_15'],
          $communityData['HINCY15_20'],
          $communityData['HINCY20_25'],
           $communityData['HINCY25_30'],
          $communityData['HINCY30_35'],
          $communityData['HINCY35_40'],
          $communityData['HINCY40_45'],
          $communityData['HINCY45_50'],
           $communityData['HINCY50_60'],
          communityData['HINCY60_75'],
           $communityData['HINCY75_100'],
          $communityData['HINCY100_125'],
          $communityData['HINCY125_150'],
           $communityData['HINCY150_200'],
           $communityData['HINCY200_250'],
           $communityData['HINCY250_500'],
           $communityData['HINCYGT_500']


        });*/
}

//  Zip code Area highlight
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

function removeShape(e){
    deleteSelectedShape();
    if (e.type != google.maps.drawing.OverlayType.MARKER) {
        drawingManager.setDrawingMode(null);
        var newShape = e.overlay;
        newShape.type = e.type;
        setSelection(newShape);
    }
}

function deleteAllShape() {
  for (var i = 0; i < all_overlays.length; i++) {
    all_overlays[i].overlay.setMap(null);
  }
  all_overlays = [];
}

function resetMarker(){
    deleteAllShape();
    $('.swiper-wrapper').empty();
    
    f(locationLatLng);
    $("#searchCount").text("Property Count :" + locationLatLng.length);
    count = locationLatLng.length;
    for(j =0 ;j < count ;j++){
        var text = '<div class="swiper-slide" id="'+locationLatLng[j][0]+','+locationLatLng[j][1]+'" style="height: 100px;">' +
                    '<div class="box selectPOI">' +
                    '<span class="h3 hotlineLabel ' + "visited" + '" target="_blank" lat ="' + locationLatLng[j][0] + '" long = "' + locationLatLng[j][1] + '" line1 = "' + encodeURI(locationLatLng[j][3]) + '" line2="' + encodeURI(locationLatLng[j][4]) + '"oneline="' + (locationLatLng[j][2]).replace( '#', "") + '" > Hot Property </span>' +
                    '<div class="float-right">' +
                    '<input type="checkbox" name="selectedItem" class="selectedProperty" aria-label="Checkbox for following text input">' +
                    '<button type="button" class="saveBtn btn btn-link"  line1 = "' + encodeURI(locationLatLng[j][2]) + '" line2="' + encodeURI(locationLatLng[j][2]) + '" style="padding: 5px;"><i class="fas fa-save" style="color: black;"></i></button>' +
                    '</div>' +
                    '<div class="float-left">' +
                    '<img width="250px" src="https://maps.googleapis.com/maps/api/streetview?size=250x250&location=' + locationLatLng[j][2] + '&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">' +
                    '</div></div></div>';
        swiper.appendSlide(text);
    }
    
}

var map;
var myLatLng = [];
function init() {
    var i;
    var tmp;
    var myOptions = {
        zoom: 13,
        center: new google.maps.LatLng(lat, lng),
        scrollwheel : true,
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);

    marker;
    infowindow = new google.maps.InfoWindow();
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, lng),
        map: map,
        animation: google.maps.Animation.DROP,
    });

    drawingManager = new google.maps.drawing.DrawingManager({
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: ['circle', 'polygon', 'rectangle']
        },
        markerOptions: {icon: 'Img/icons/pin_b.png'},
        circleOptions: {
            fillColor: 'red',
            strokeWeight: 1,
        },
        polygonOptions: {
            fillColor: 'red',
            strokeWeight: 1,
        },
        rectangleOptions: {
            fillColor: 'red',
            strokeWeight: 1,
        }
    });
    drawingManager.setMap(map);   
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e,polygon) {
        all_overlays.push(e);
        setMapOnAll(null);
        removeShape(e);
        // focusonmarker(0);
        homemarkers = [];
        
        const latlongCount = [...new Set(locationLatLng.map(x => x[0]+x[1]))];
        
        var count = 0;
        $('.swiper-wrapper').empty();
        for(var j=0;j< latlongCount.length;j++){

            var coordinate = new google.maps.LatLng(locationLatLng[j][0], locationLatLng[j][1]);
            // console.log(coordinate, ' coordinate');
            if(e.type == "rectangle"){
                
                var boundssss = e.overlay.getBounds();
                var start = boundssss.getNorthEast();
                var end = boundssss.getSouthWest();
                var latlng = [{lat : start.lat(), lng : start.lng()},
                                {lat : start.lat(), lng : end.lng()},
                                {lat : end.lat(), lng : end.lng()},
                                {lat : end.lat(), lng : start.lng()},
                             ]
                
                var bermudaTriangle = new google.maps.Polygon({
                    paths: latlng
                  });
                
                var isWithinRectangle = google.maps.geometry.poly.containsLocation(coordinate, bermudaTriangle);
                if(isWithinRectangle){

                    count ++;
                    $('#searchCount').text("Property count : " + count);
                    $("#poiContent").show();         
                    markers = new google.maps.Marker({
                        position: coordinate,
                        animation: google.maps.Animation.DROP,
                        map: map,
                        icon: 'Img/icons/pin_b.png'
                    });
                    var text = '<div class="swiper-slide" id="'+locationLatLng[j][0]+','+locationLatLng[j][1]+'" style="height: 100px;">' +
                        '<div class="box selectPOI">' +
                        '<span class="h3 hotlineLabel ' + "visited" + '" target="_blank" lat ="' + locationLatLng[j][0] + '" long = "' + locationLatLng[j][1] + '" line1 = "' + encodeURI(locationLatLng[j][3]) + '" line2="' + encodeURI(locationLatLng[j][4]) + '"oneline="' + (locationLatLng[j][2]).replace( '#', "") + '" > Hot Property </span>' +
                        '<div class="float-right">' +
                        '<input type="checkbox" name="selectedItem" class="selectedProperty" aria-label="Checkbox for following text input">' +
                        '<button type="button" class="saveBtn btn btn-link"  line1 = "' + encodeURI(locationLatLng[j][2]) + '" line2="' + encodeURI(locationLatLng[j][2]) + '" style="padding: 5px;"><i class="fas fa-save" style="color: black;"></i></button>' +
                        '</div>' +
                        '<div class="float-left">' +
                        '<img width="250px" src="https://maps.googleapis.com/maps/api/streetview?size=250x250&location=' + locationLatLng[j][2] + '&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">' +
                        '</div></div></div>';
                    swiper.appendSlide(text);
                    
                    google.maps.event.addListener(markers, 'click', (function (markers, j) {
                        var html = "<a style='color: #007bff;cursor: pointer;font-weight: bold;font-size: 14px;' line1='"+locationLatLng[j][3]+"' line2='"+locationLatLng[j][4]+"' id='SavePro'>Save Property</a>";
                        var content = "<p>"+locationLatLng[j][2]+"</p>";
                        var html2 = "<br><a href='/getOwnerDetail/"+encodeURI(locationLatLng[j][3])+"/"+encodeURI(locationLatLng[j][4])+"' style='color: #007bff;cursor: pointer;font-weight: bold;font-size: 14px;' target='_blank' id='DetailPro'>Property Detail</a>";
                        var latLngSlider = locationLatLng[j][0]+","+locationLatLng[j][1];
                        return function () {
                            infowindow.setContent(html);
                            infowindow.open(map, markers);
                            swiper.slideTo(markers.get("id"));
                            swiper.updateSlidesClasses();
                            
                        }
                    })(markers, j))
                    
                    markers.set("id", homemarkers.length);
                    homemarkers.push(markers);
                    // focusonmarker(0);
                    
                }else{
                    $('#searchCount').text("Property count : " + count);
                }
            }else if(e.type == "polygon"){
            
                var isWithinPolygon = google.maps.geometry.poly.containsLocation(coordinate, e.overlay); 
                
                if(isWithinPolygon){

                    count ++;
                    $('#searchCount').text("Property count : " + count);
                    $("#poiContent").show();         
                    markers = new google.maps.Marker({
                        position: coordinate,
                        animation: google.maps.Animation.DROP,
                        map: map,
                        icon: 'Img/icons/pin_b.png'
                    });
                    var text = '<div class="swiper-slide" id="'+locationLatLng[j][0]+','+locationLatLng[j][1]+'" style="height: 100px;">' +
                        '<div class="box selectPOI">' +
                        '<span class="h3 hotlineLabel ' + "visited" + '" target="_blank" lat ="' + locationLatLng[j][0] + '" long = "' + locationLatLng[j][1] + '" line1 = "' + encodeURI(locationLatLng[j][3]) + '" line2="' + encodeURI(locationLatLng[j][4]) + '"oneline="' + (locationLatLng[j][2]).replace( '#', "") + '" > Hot Property </span>' +
                        '<div class="float-right">' +
                        '<input type="checkbox" name="selectedItem" class="selectedProperty" aria-label="Checkbox for following text input">' +
                        '<button type="button" class="saveBtn btn btn-link"  line1 = "' + encodeURI(locationLatLng[j][2]) + '" line2="' + encodeURI(locationLatLng[j][2]) + '" style="padding: 5px;"><i class="fas fa-save" style="color: black;"></i></button>' +
                        '</div>' +
                        '<div class="float-left">' +
                        '<img width="250px" src="https://maps.googleapis.com/maps/api/streetview?size=250x250&location=' + locationLatLng[j][2] + '&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">' +
                        '</div></div></div>';
                    swiper.appendSlide(text);
                    
                    google.maps.event.addListener(markers, 'click', (function (markers, j) {

                        var html = "<a style='color: #007bff;cursor: pointer;font-weight: bold;font-size: 14px;' line1='"+locationLatLng[j][3]+"' line2='"+locationLatLng[j][4]+"' id='SavePro'>Save Property</a>";
                        var html2 = "<br><a href='/getOwnerDetail/"+encodeURI(locationLatLng[j][3])+"/"+encodeURI(locationLatLng[j][4])+"' style='color: #007bff;cursor: pointer;font-weight: bold;font-size: 14px;' target='_blank' id='DetailPro'>Property Detail</a>";
                        var content = "<p>"+locationLatLng[j][2]+"</p>";
                        var latLngSlider = locationLatLng[j][0]+","+locationLatLng[j][1];
                        return function () {
                            infowindow.setContent(html);
                            infowindow.open(map, markers);
                            swiper.slideTo(markers.get("id"));
                            swiper.updateSlidesClasses();
                           
                        }
                    })(markers, j))
                    
                    markers.set("id", homemarkers.length);
                    homemarkers.push(markers);
                    // focusonmarker(0);
                    
                }else{
                    $('#searchCount').text("Property count : " + count);
                }
            }else if(e.type == "circle"){
                
                var circle = e.overlay;
                var radius = circle.getRadius();
                var center = circle.getCenter();
                var isWithinCircle = google.maps.geometry.spherical.computeDistanceBetween(coordinate, center);
                if(isWithinCircle <= radius){
                    console.log(isWithinCircle);
                    count ++;
                    $('#searchCount').text("Property count : " + count);
                    $("#poiContent").show();         
                    markers = new google.maps.Marker({
                        position: coordinate,
                        animation: google.maps.Animation.DROP,
                        map: map,
                        icon: 'Img/icons/pin_b.png'
                    });
                    var text = '<div class="swiper-slide" id="'+locationLatLng[j][0]+','+locationLatLng[j][1]+'" style="height: 100px;">' +
                        '<div class="box selectPOI">' +
                        '<span class="h3 hotlineLabel ' + "visited" + '" target="_blank" lat ="' + locationLatLng[j][0] + '" long = "' + locationLatLng[j][1] + '" line1 = "' + encodeURI(locationLatLng[j][3]) + '" line2="' + encodeURI(locationLatLng[j][4]) + '"oneline="' + (locationLatLng[j][2]).replace( '#', "") + '" > Hot Property </span>' +
                        '<div class="float-right">' +
                        '<input type="checkbox" name="selectedItem" class="selectedProperty" aria-label="Checkbox for following text input">' +
                        '<button type="button" class="saveBtn btn btn-link"  line1 = "' + encodeURI(locationLatLng[j][2]) + '" line2="' + encodeURI(locationLatLng[j][2]) + '" style="padding: 5px;"><i class="fas fa-save" style="color: black;"></i></button>' +
                        '</div>' +
                        '<div class="float-left">' +
                        '<img width="250px" src="https://maps.googleapis.com/maps/api/streetview?size=250x250&location=' + locationLatLng[j][2] + '&pitch=-0.76&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74" alt="">' +
                        '</div></div></div>';
                    swiper.appendSlide(text);
                    console.log(locationLatLng[j][2]);
                    google.maps.event.addListener(markers, 'click', (function (markers, j) {

                        var html ="<a style='color: #007bff;cursor: pointer;font-weight: bold;font-size: 14px;' line1='"+locationLatLng[j][3]+"' line2='"+locationLatLng[j][4]+"' id='SavePro'>Save Property</a>";
                        var html2 = "<br><a href='/getOwnerDetail/"+encodeURI(locationLatLng[j][3])+"/"+encodeURI(locationLatLng[j][4])+"' style='color: #007bff;cursor: pointer;font-weight: bold;font-size: 14px;' target='_blank' id='DetailPro'>Property Detail</a>";
                        var content = "<p>"+locationLatLng[j][2]+"</p>";
                        var latLngSlider = locationLatLng[j][0]+","+locationLatLng[j][1];
                        return function () {
                            infowindow.setContent(html);
                            infowindow.open(map, markers);
                            swiper.slideTo(markers.get("id"));
                            swiper.updateSlidesClasses();
                        }
                    })(markers, j))
                    
                    markers.set("id", homemarkers.length);
                    homemarkers.push(markers);
                    // focusonmarker(0);
                    
                }else{
                    $('#searchCount').text("Property count : " + count);
                }
            }
        }
    });

    /*for (i = 0; i < polys.length; i++) {
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
    }*/

    map.setOptions({styles: styles['hide']});

    $('#map').css('height', '450px');
    return map;
}

//send Email
$("#sendEmail").click((e) => {
    e.preventDefault();
    let i = 0;
    let listArray = [];
    if ($("#emailaddress").val() == "") {
        $("#emailHelp").text("Please Enter Email address")
        return "";
    } else {
        if (!$("#emailaddress")[0].checkValidity()) {
            $("#emailHelp").text("Please Enter Correct Email address")
            return "";

        } else {
            $("#emailHelp").text("")
        }
    }
    $('input[name="selectedItem"]:checked').each(function () {
        listArray.push([$(this).closest('.box').find('a').attr('line1'), $(this).closest('.box').find('a').attr('line2')]);
    });


    fetch("/sendMail", {
        method: "post", // *GET, POST, PUT, DELETE, etc.
        mode: "cors", // no-cors, cors, *same-origin
        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
        credentials: "same-origin", // include, *same-origin, omit
        body: JSON.stringify({addressList: JSON.stringify(listArray), email: $("#emailaddress").val()}),
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
            console.log(data);
            if (data[0] == "send") {
                $.notify({
                    // options
                    icon: 'fas fa-check-circle',
                    message: 'Email Send Successfully'
                }, {
                    // settings
                    type: 'success'
                });
            } else {
                $.notify({
                    // options
                    icon: 'fa fa-exclamation-circle',
                    message: 'Not able to send Email'
                }, {
                    // settings
                    type: 'danger'
                });
            }
        })
    $("input[type='checkbox']").click(function () {
        if ($('input:checkbox:checked').length > 10) {
            alert("Email send limit is 10");
            $(this).attr('checked', false)
        }
    });


    /*$.ajax({
        type:'post',
        url:'/sendMail',
        data:{addressList:JSON.stringify(listArray)},
        success:function(data){
            console.log(data);
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log("Please Wait to load all the data");
        },
        timeout: 5000
    });*/
})




















