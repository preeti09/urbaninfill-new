<?php

namespace App\Http\Controllers;

use App\Mailertemplate;
use App\Properties;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AjaxController extends Controller
{
    // private $obapiurl = 'http://search.onboard-apis.com', $obapikey = 'e702e26e93b183f8d1dfd7ba9dc05390';
    private $obapiurl = 'http://search.onboard-apis.com', $obapikey = 'f43450c0dc7254e59139c4df94ae66b9';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sendPropertymail(Request $request)
    {
        $content = $request->input('content');
        $propertymail = $request->input('propertyEmailTxt');
        return $this->sendMail(nl2br($content), $propertymail);
    }

    private function sendMail($completeView, $emailAdress)
    {

        try {
            Mail::send([], [], function ($message) use ($completeView, $emailAdress) {
                $message->to($emailAdress, $emailAdress)->subject("Property List");
                $message->from("masterofinfill@gmail.com", "urbaninfill")->setBody($completeView, 'text/html');
            });
        } catch (\Exception $e) {
            return array([$e->getMessage(), "UrbanInfillApp@gmaisl.com"]);
        }
        return array("send");
    }

    public function showmail(Request $request, $fullname, $fulladdress, $emailaddress, $template = null)
    {
        $Currentuser = User::find($request->user()->id);
        $allTemplates = Mailertemplate::all();
        $currentTemplate = Mailertemplate::find($template);
        $data = str_replace('((username))', $Currentuser->name, $currentTemplate->content);
        $data = str_replace('((phonenumber))', $Currentuser->PhoneNumber, $data);
        $data = str_replace('((address))', $fulladdress, $data);
        $data = str_replace('((ownername))', $fullname, $data);
        $data = str_replace('((companyaddress))', $Currentuser->Address, $data);
        $data = str_replace('((companyname))', $Currentuser->companyname, $data);
        if (isset($Currentuser->logo))
            $data = str_replace('((logo))', '<img src="https://lothub-storage.s3-us-west-2.amazonaws.com/' . $Currentuser->logo . '" width="100px" height="100px"/>', $data);

        else
            $data = str_replace('((logo))', '<img src="https://upload.wikimedia.org/wikipedia/commons/6/6c/No_image_3x4.svg" width="100px" height="100px"/>', $data);

        return view('propertyEmail')->with('data', $data)->with('fullname', $fullname)->with('fulladdress', $fulladdress)->with('allTemplates', $allTemplates)->with('emailaddress', $emailaddress);
    }

    public function persondetail($fname, $lname, $index)
    {
        $getList = $this->DetailPersonInformation($fname, $lname, $index);

        return view('personDetail')->with("persondetail", $getList["result"][0]);
    }

    private function DetailPersonInformation($fName, $lName, $index)
    {
        // Get Token
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://login-api-test.idicore.com/apiclient",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "glba=otheruse&dppa=none&undefined=",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic YXBpLWNsaWVudEB1cmJhbmluZmlsbHRlc3Q6WXc4dVZpMkohJnZnNXdmdVVCc2tXWkYmUG05QU5xWm5VSnQ0eVd2U1lYNDdQTXN5MlROQW1uVkMzJlp1JEdqTg==",
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            return json_decode($err, true);
        } else {
            $req_token = $response;
        }

        // Make API request
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-test.idicore.com/search",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => "{ \n\"pidlist\":[\"$index\"],\n\"firstName\":\"$fName\",\n\"lastName\":\"$lName\",\n\"fields\":[\"ssn\",\"name\",\"phone\",\"email\",\"address\",\"dob\",\"relationship\",\"property\",\"motorVehicle\",\"bankruptcy\",\"employment\",\"criminal\",\"lien\",\"judgment\",\"death\",\"ip\",\"military\",\"death\"]\n\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: $req_token",
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return json_decode($err, true);
        } else {
            return json_decode($response, true);
        }
    }

    public function personlist(Request $request)
    {
        $fName = $request->input('fName');
        $lName = $request->input('lName');
        $zip = $request->input('zip');
        $getList = $this->getOwnerInformationbyName($fName, $lName, $zip);
        return $getList;
    }

    private function getOwnerInformationbyName($fName, $lName, $zip)
    {
        // Get Token
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://login-api-test.idicore.com/apiclient",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "glba=otheruse&dppa=none&undefined=",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic YXBpLWNsaWVudEB1cmJhbmluZmlsbHRlc3Q6WXc4dVZpMkohJnZnNXdmdVVCc2tXWkYmUG05QU5xWm5VSnQ0eVd2U1lYNDdQTXN5MlROQW1uVkMzJlp1JEdqTg==",
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            return json_decode($err, true);
        } else {
            $req_token = $response;
        }

        // Make API request
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-test.idicore.com/search",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => "{ \n\"zip\":\"$zip\",\n\"firstName\":\"$fName\",\n\"lastName\":\"$lName\",\n\"fields\":[\"ssn\",\"name\",\"phone\",\"email\",\"address\",\"dob\",\"relationship\",\"property\",\"motorVehicle\",\"bankruptcy\",\"employment\",\"criminal\",\"lien\",\"judgment\",\"death\",\"ip\",\"military\",\"death\"]\n\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: $req_token",
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return json_decode($err, true);
        } else {
            return json_decode($response, true);
        }
    }

    public function saveProperty(Request $request)
    {
        //return $request->user();
        $getCurrentUser = User::find($request->user()->id);
        if ($getCurrentUser->IsSavedPropertyRest) {
            $getCurrentUser->SavedPropertyFirstDate = date("Y-m-d H:i:s");
            // $getCurrentUser->IsSavedPropertyRest = false;

            //Added by bhavana
            $getCurrentUser->IsSavedPropertyRest = $getCurrentUser->IsSavedPropertyRest - 1;
            $getCurrentUser->save();
            $getCurrentUser = User::find($request->user()->id);
        }
        if ($getCurrentUser->savedcount > 1) {
            $line1 = $request->input('line1');
            $line2 = $request->input('line2');
            $property = new Properties;
            $property->user_id = $request->user()->id;
            $property->line1 = $line1;
            $property->line2 = $line2;
            $property->save();
            $getCurrentUser->savedcount = $getCurrentUser->savedcount - 1;
            $getCurrentUser->save();
            return "Success";
        }
        return "Error";
    }

    public function geocode_public($address)
    {
        return $this->geocode($address);
    }

    private function geocode($address)
    {

        // url encode the address
        $address = urlencode($address);

        // google map geocode api url
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyAInrucxqh4SXD1SZcpjFIZq9EnDjD-k74";

        // get the json response
        $resp_json = file_get_contents($url);

        // decode the json
        $resp = json_decode($resp_json, true);

        // response status will be 'OK', if able to geocode given address
        if ($resp['status'] == 'OK') {

            // get the important data
            $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
            $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
            $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
            $addressComponents = $resp['results'][0]['address_components'];
            foreach ($addressComponents as $addrComp) {
                if ($addrComp['types'][0] == 'postal_code') {
                    //Return the zipcode
                    $zip = $addrComp['long_name'];
                }
            }
            // verify if data is complete
            if ($lati && $longi && $formatted_address) {

                // put the data in the array
                $data_arr = array();

                array_push(
                    $data_arr,
                    $lati,
                    $longi,
                    $formatted_address
                );

                return $formatted_address . '; ' . $zip;

            } else {
                return "false";
            }

        } else {
            //echo "<strong>ERROR: {$resp['status']}</strong>";
            return "false";
        }
    }

    public function PropertypoiData($line, $zip)
    {
        $businessCat = "";
        $address = $this->geocode($line);
        $poiData = $this->getPoiData(urlencode($address), 5);
        $communityData = $this->getCommunityByAreaIdzip('ZI' . $zip);
        $nearestSport = $communityData[0]->TEAM;
        $nearestAirport = $communityData[0]->AIRPORT;

        $distanceSortedArr = array();
        $distanceSortedCatonlyArr = array();

        if (@$poiData['response']['status']['code'] == 0) {
            //$mapAmenities = $poiData['response']['result']['package']['item'];

            $sourceLocationLatitude = $poiData['response']['result']['package']['item'][0]['geo_latitude'];
            $sourceLocationLongitude = $poiData['response']['result']['package']['item'][0]['geo_longitude'];
            foreach ($poiData['response']['result']['package']['item'] as $amenities) {
                if ($businessCat != '' && is_array($businessCat)) {
                    if (in_array(strtolower($amenities['business_category']), $businessCat)) {
                        $distanceSortedArr[$amenities['distance']][] = $amenities;
                        $distanceSortedCatonlyArr[$amenities['distance']][] = strtolower(str_replace(array(" - ", " "), array('-', '-'), $amenities['business_category']));
                        $amenitiesData[ucwords(strtolower($amenities['business_category']))][] = $amenities;
                    }
                } else {
                    $distanceSortedArr[$amenities['distance']][] = $amenities;
                    $amenitiesData[ucwords(strtolower($amenities['business_category']))][] = $amenities;
                    $distanceSortedCatonlyArr[$amenities['distance']][] = strtolower(str_replace(array(" - ", " "), array('-', '-'), $amenities['business_category']));
                }
            }

            ksort($amenitiesData);
            ksort($distanceSortedArr);
            ksort($distanceSortedCatonlyArr);


            //echo '<pre>';print_r($distanceSortedArr);
            //echo '<pre>';print_r($distanceSortedCatonlyArr);die;

            $divideBy = round(count($amenitiesData) / 2);
            $splitAmenityData = array_chunk($amenitiesData, $divideBy, true);

            return view("propertyPOI")->with('splitAmenityData', $splitAmenityData)->with('sourceLocationLongitude', $sourceLocationLongitude)->with('sourceLocationLatitude', $sourceLocationLatitude)->with('distanceSortedArr', $distanceSortedArr)->with('distanceSortedCatonlyArr', $distanceSortedCatonlyArr)->with('nearestSport', $nearestSport)->with('nearestAirport', $nearestAirport)->with('communityData', $communityData);
        } else {
            return "no data";
        }
    }

    public function getPoiData($address, $radius)
    {
        $url = $this->obapiurl . "/poisearch/v2.0.0/poi/street+address?StreetAddress=" . $address . "&SearchDistance=" . $radius . "&RecordLimit=50";
        return $this->curlPOIAPI($url);
    }

    private function curlPOIAPI($url, $apiKey = null)
    {

        $curl = curl_init(); //cURL initialization

        //Set cURL array with require params
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 1000,
            CURLOPT_TCP_KEEPALIVE => 50,
            CURLOPT_TCP_KEEPIDLE => 100,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "apikey: " . ($apiKey != '' ? $apiKey : $this->obapikey)

            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        //echo "<pre>"; print_r($err); die;
        curl_close($curl);

        if ($err) {
            return '{"status": { "code": 999, "msg": "cURL Error #:"' . $err . '"}}';
        } else {
            return json_decode($response, true);
        }
    }

    public function getCommunityByAreaIdzip($areaid)
    {
        $url = $this->obapiurl . "/communityapi/v2.0.0/area/full?AreaId=" . $areaid;

        $result_community1 = $this->curlPOIAPI($url);

        $communityData = array();

        if (count(@$result_community1['response']['result']['package']['item']) > 0) {
            foreach ($result_community1['response']['result']['package']['item'][0] as $resultCommKey => $resultCommVal) {
                $communityData[strtoupper($resultCommKey)] = $resultCommVal;
            }
        }

        $communityData1[0] = $communityData;

        $communityDataFinal = json_decode(json_encode($communityData1), FALSE);

        return $communityDataFinal;
    }

    public function getzipResponse(Request $request)
    {
        $address = $request->input('address');
        $location = $request->input('location');
        $zip = $request->input('zip');
        $AreaHierarchy = $this->getAreaHierarchy($location[0], $location[1]);

        $geoARRAY = array();
        $geoValName = array();
        foreach ($AreaHierarchy['response']['result']['package']['item'] as $key => $area) {
            $geoARRAY[] = $area['geo_key'];
            $geoValName[$area['geo_key']] = $area['name'];
        }
        $boundary = $this->getAreaBoundary($geoARRAY[0]);

        return response($boundary['response']['result']['package']['item'][0]['boundary']);
    }

    private function getAreaHierarchy($lat, $long)
    {
        $location = urlencode($long . ',' . $lat);
        $url = $this->obapiurl . "/areaapi/v2.0.0/hierarchy/lookup?WKTString=POINT(" . $location . ")&geoType=ZI&debug=True";
        return $this->curlPOIAPI($url);
    }

    private function getAreaBoundary($areaid)
    {
        $url = $this->obapiurl . '/areaapi/v2.0.0/boundary/detail?AreaId=' . $areaid . '&debug=True';
        return $this->curlPOIAPI($url);
    }

    public function ExtendedDetail(Request $request, $line1, $line2)
    {

        if ($request->user()->authorizeRoles(['user'])) {
            $result = $this->getallevent(urlencode($line1), urlencode($line2));

            $AVMResult = $this->getdetailmortgageowner(urlencode($line1), urlencode($line2));
            // dd($result);
            $information = [];//$this->getOwnerInformation($line1, $AVMResult["property"][0]["address"]["countrySubd"], $AVMResult["property"][0]["address"]["postal1"]);

            
            $psArray = array();
            foreach ($AVMResult["property"] as $key => $data) {
                $AssessmentHistory = $this->getAssessmentHistory($data["identifier"]["obPropId"]);
                if ($AssessmentHistory["property"] == null) {

                } else
                    $psArray[$data["identifier"]["obPropId"]] = $AssessmentHistory["property"][0]["assessmenthistory"];
            }
            
            $fullName = 'Home Owner';
            if (array_key_exists('owner1', $AVMResult["property"][0]["owner"])) {
                $fullName = '';
                foreach ($AVMResult["property"][0]["owner"]["owner1"] as $key => $value) {
                    $fullName = $fullName . $value . " ";
                }
            }

            // url encode the address
            $address = urlencode($line1 . ' ' . $line2);

            // google map geocode api url
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyCPAVKxutIiPNXJr8UeB2wwSrzrFA3-GuI";

            // get the json response
            $resp_json = file_get_contents($url);

            // decode the json
            $resp = json_decode($resp_json, true);
            $lati = 0;
            $longi=0;

            // response status will be 'OK', if able to geocode given address
            if ($resp['status'] == 'OK') {

                // get the important data
                $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
                $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
            }


            return view('DetailPage')->with('result', $result)->with("AVMResult", $AVMResult)->with("Assessment", $psArray)->with("OwnerInfo", $information)->with('fullname', $fullName)->with('fulladdress', str_replace("#","",($line1 . ' ' . $line2)))->with('lat',$lati)->with ('longi',$longi);
        } else
            return redirect('/logout');
    }

    //Added by bhavana
    public function getAjaxResponse(Request $request, $line1, $line2){
        if ($request->user()->authorizeRoles(['user'])) {
            $result = $this->getallevent(urlencode($line1), urlencode($line2));

            $AVMResult = $this->getdetailmortgageowner(urlencode($line1), urlencode($line2));
            
            $information = [];
            
            $psArray = array();
            foreach ($AVMResult["property"] as $key => $data) {
                $AssessmentHistory = $this->getAssessmentHistory($data["identifier"]["obPropId"]);
                if ($AssessmentHistory["property"] == null) {

                } else
                    $psArray[$data["identifier"]["obPropId"]] = $AssessmentHistory["property"][0]["assessmenthistory"];
            }
            
            $fullName = 'Home Owner';
            if (array_key_exists('owner1', $AVMResult["property"][0]["owner"])) {
                $fullName = '';
                foreach ($AVMResult["property"][0]["owner"]["owner1"] as $key => $value) {
                    $fullName = $fullName . $value . " ";
                }
            }

            // url encode the address
            $address = urlencode($line1 . ' ' . $line2);

            // google map geocode api url
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyCPAVKxutIiPNXJr8UeB2wwSrzrFA3-GuI";

            // get the json response
            $resp_json = file_get_contents($url);

            // decode the json
            $resp = json_decode($resp_json, true);
            $lati = 0;
            $longi=0;

            // response status will be 'OK', if able to geocode given address
            if ($resp['status'] == 'OK') {

                // get the important data
                $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
                $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
            }

            return view('ajax_response')->with('result', $result)->with("AVMResult", $AVMResult)->with("Assessment", $psArray)->with("OwnerInfo", $information)->with('fullname', $fullName)->with('fulladdress', str_replace("#","",($line1 . ' ' . $line2)))->with('lat',$lati)->with ('longi',$longi);

            
        } else
            return redirect('/logout');
    }

    private function getallevent($line1, $line2)
    {
        $url = $this->obapiurl . '/propertyapi/v1.0.0/allevents/detail?address1=' . $line1 . '&address2=' . $line2 . '&debug=True';
        return $this->curlPOIAPI($url);
    }

    private function getdetailmortgageowner($line1, $line2)
    {
        $url = $this->obapiurl . '/propertyapi/v1.0.0/property/detailmortgageowner?address1=' . $line1 . '&address2=' . $line2 . '&debug=True';
        return $this->curlPOIAPI($url);
    }


    private function getOwnerInformation($lineOne, $state, $zip)
    {
        // Get Token
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://login-api-test.idicore.com/apiclient",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "glba=otheruse&dppa=none&undefined=",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic YXBpLWNsaWVudEB1cmJhbmluZmlsbHRlc3Q6WXc4dVZpMkohJnZnNXdmdVVCc2tXWkYmUG05QU5xWm5VSnQ0eVd2U1lYNDdQTXN5MlROQW1uVkMzJlp1JEdqTg==",
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            return json_decode($err, true);
        } else {
            $req_token = $response;
        }

        // Make API request
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-test.idicore.com/search",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => "{ \n\"zip\":\"$zip\",\n\"address\":\"$lineOne\",\n\"state\":\"$state\",\n\"fields\":[\"name\",\"phone\",\"email\",\"address\",\"dob\",\"relationship\",\"property\",\"motorVehicle\",\"bankruptcy\",\"employment\",\"criminal\",\"lien\",\"judgment\"]\n\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: $req_token",
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return json_decode($err, true);
        } else {
            return json_decode($response, true);
        }
    }

    private function getAssessmentHistory($id)
    {
        $url = $this->obapiurl . '/propertyapi/v1.0.0/assessmenthistory/detail?id=' . $id . '&debug=True';
        return $this->curlPOIAPI($url);
    }

    public function allpropertiesList(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $page = $request->input('page');
        $zip = $request->input('zip');
        $zip = urlencode($zip);
        $pagesize = 1000;
        //$url = $this->obapiurl . '/propertyapi/v1.0.0/property/detail?latitude=' . $lat . '&longitude=' . $lng . '&page=' . $page . '&pagesize=' . $pagesize . '&debug=True';
        $url = 'https://api.gateway.attomdata.com/propertyapi/v1.0.0/property/detail?postalcode=' . $zip . '&page=' . $page . '&pagesize=' . $pagesize;
        $result = $this->curlPOIAPI($url);
        echo json_encode(($result));
    }
    public function getpropertiesByTypeList(Request $request)
    {
        $zip = $request->input('postalcode');
        $type = $request->input('type');
        $page = $request->input('page');

        $zip = urlencode($zip);
        $type = urlencode($type);
        $pagesize = 1000;
        $url = 'https://api.gateway.attomdata.com/propertyapi/v1.0.0/property/address?postalcode='.$zip.'&propertytype='.$type.'&page='.$page.'&pagesize='.$pagesize. '&debug=True';
        //$url = $this->obapiurl . '/propertyapi/v1.0.0/property/detail?latitude=' . $lat . '&longitude=' . $lng . '&page=' . $page . '&pagesize=' . $pagesize . '&debug=True';
        //$url = $this->obapiurl . '/propertyapi/v1.0.0/property/detail?postalcode=' . $zip . '&page=' . $page . '&pagesize=' . $pagesize;
        $result = $this->curlPOIAPI($url);
        echo json_encode(($result));
    }
    public function getHouseInventry(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $AreaHierarchy = $this->getAreaHierarchy($lat, $lng);
        //return $AreaHierarchy;
        $geoARRAY = array();
        $geoValName = array();
        foreach ($AreaHierarchy['response']['result']['package']['item'] as $key => $area) {
            $geoARRAY[] = $area['geo_key'];
            $geoValName[$area['geo_key']] = $area['name'];
        }
        $communityData = $this->getCommunityByAreaId1($geoARRAY[0]);
        return response($communityData);
    }

    public function getCommunityByAreaId1($areaid)
    {
        $url = $this->obapiurl . "/communityapi/v2.0.0/area/full?AreaId=" . $areaid;

        $result_community1 = $this->curlPOIAPI($url);

        $communityData = array();

        if (count(@$result_community1['response']['result']['package']['item']) > 0) {
            foreach ($result_community1['response']['result']['package']['item'][0] as $resultCommKey => $resultCommVal) {
                $communityData[strtoupper($resultCommKey)] = $resultCommVal;
            }
        }

        //$communityData1[0] = $communityData;

        //$communityDataFinal = json_decode (json_encode ($communityData1), FALSE);

        return $communityData;
    }

    public function getTotalPages(Request $request)
    {
        $zip = $request->input('zip');
        $isVacant = $request->input('isVacant');

        $getCurrentUser = User::find($request->user()->id);
        if ($getCurrentUser->IsHistoricRest && $isVacant == 'false') {
            $getCurrentUser->HistoricFirstDate = date("Y-m-d H:i:s");
            $getCurrentUser->IsHistoricRest = false;

            $getCurrentUser->save();
            $getCurrentUser = User::find($request->user()->id);
        } else if ($getCurrentUser->IsVacantRest && $isVacant == 'true') {
            $getCurrentUser->VacantFirstDate = date("Y-m-d H:i:s");
            $getCurrentUser->IsVacantRest = false;
            $getCurrentUser->save();
            $getCurrentUser = User::find($request->user()->id);
        }

        if ($getCurrentUser->Historicsavedcount > 0 && $isVacant == 'false') {


            $pagesize = 1;
            $page = 1;
            $url = 'https://api.gateway.attomdata.com/propertyapi/v1.0.0/property/detail?postalcode=' . $zip . '&page=' . $page . '&pagesize=' . $pagesize;
            $result = $this->curlPOIAPI($url);
            $total = $result['status']['total'];
            $getCurrentUser->Historicsavedcount = $getCurrentUser->Historicsavedcount - 1;
            $getCurrentUser->save();
            $totalPages = $total / 1000;
            return response($totalPages);
        } else if ($getCurrentUser->Vacantsavedcount > 0 && $isVacant == 'true') {


            $pagesize = 1;
            $page = 1;
            $url = 'https://api.gateway.attomdata.com/propertyapi/v1.0.0/property/detail?postalcode=' . $zip . '&page=' . $page . '&pagesize=' . $pagesize;
            $result = $this->curlPOIAPI($url);
            $total = $result['status']['total'];
            $getCurrentUser->Historicsavedcount = $getCurrentUser->Historicsavedcount - 1;
            $getCurrentUser->save();
            $totalPages = $total / 1000;
            return response($totalPages);
        } else {
            return response('Error');
        }


    }
    public function getTotalTypePages(Request $request)
    {
        $zip = $request->input('postalcode');
        $type = $request->input('type');

        $zip = urlencode($zip);
        $type = urlencode($type);
        $pagesize = 1;
        $page = 1;

        $url = $this->obapiurl .'/propertyapi/v1.0.0/property/address?postalcode='.$zip.'&propertytype='.$type.'&page='.$page.'&pagesize='.$pagesize.'&debug=True';
        $result = $this->curlPOIAPI($url);
        $total = $result['status']['total'];
        $totalPages = $total / 1000;
        return response($totalPages);



    }

    public function getPropertyResponse(Request $request)
    {

        $address = $request->input('address');
        //$address = ;

        $address = $this->geocode($address);
        $propertyInfo = $this->getPropertyDetail($address);
        $final_array = $this->getSchoolDemographicData($propertyInfo["property"][0]["location"]["latitude"], $propertyInfo["property"][0]["location"]["longitude"], $propertyInfo["property"][0]["address"]["line1"], $propertyInfo["property"][0]["address"]["line2"]);
        //return $final_array;
        //$context = array("legaladdress" => $propertyInfo["property"][0]["summary"]["legal1"], "view" => view('schoolPartialView')->with("$final_array",$final_array));
        $psArray = array();
        $psArray["legaladdress"] = $propertyInfo["property"][0]["summary"]["legal1"];
        $psArray["line1"] = $propertyInfo["property"][0]["address"]["line1"];
        $psArray["line2"] = $propertyInfo["property"][0]["address"]["line2"];
        $psArray["view"] = (String)view('schoolPartialView')->with("final_array", $final_array);
        $psArray["final_array"] = $final_array;
        $detailView = array();
        foreach ($final_array as $k => $schoolDetailData) {

            $detailView[$k] = (String)view('schooldetailPartialView')->with("schoolDetailData", $schoolDetailData["school_detail"]);
        }
        $psArray["lat"] = $propertyInfo["property"][0]["location"]["latitude"];
        $psArray["lng"] = $propertyInfo["property"][0]["location"]["longitude"];
        $psArray["detailViews"] = $detailView;


        $getCurrentUser = User::find($request->user()->id);
        if ($getCurrentUser->IsAddressRest) {
            $getCurrentUser->AddressFirstDate = date("Y-m-d H:i:s");
            $getCurrentUser->IsAddressRest = false;

            $getCurrentUser->save();
            $getCurrentUser = User::find($request->user()->id);
        }
        if ($getCurrentUser->Historicsavedcount > 0) {
            $getCurrentUser->Addresssavedcount = $getCurrentUser->Addresssavedcount - 1;
            $getCurrentUser->save();

            return $psArray;
        } else {
            return 'error';
        }
    }

    private function getPropertyDetail($address)
    {
        $address = urlencode($address);
        $url = $this->obapiurl . '/propertyapi/v1.0.0/property/detail?address=' . $address . '&debug=True';
        return $this->curlPOIAPI($url);
    }

    private function getSchoolDemographicData($lat, $lng, $line1, $line2)
    {
        $allPrivateSchools = $this->getSchoolSamplePrivateCode($lat, $lng);

        $psArray = array();
        if ($allPrivateSchools['status']['code'] == 0) {

            foreach ($allPrivateSchools['school'] as $pvt_s => $private_school) {
                $psArray[$pvt_s]['OBInstID'] = $private_school['Identifier']['OBInstID'];
                $psArray[$pvt_s]['InstitutionName'] = $private_school['School']['InstitutionName'];
                $psArray[$pvt_s]['GSTestRating'] = $private_school['School']['GSTestRating'];
                $psArray[$pvt_s]['gradelevel1lotext'] = $private_school['School']['gradelevel1lotext'];
                $psArray[$pvt_s]['gradelevel1hitext'] = $private_school['School']['gradelevel1hitext'];
                $psArray[$pvt_s]['Filetypetext'] = $private_school['School']['Filetypetext'];
                $psArray[$pvt_s]['geocodinglatitude'] = $private_school['School']['geocodinglatitude'];
                $psArray[$pvt_s]['geocodinglongitude'] = $private_school['School']['geocodinglongitude'];
                $psArray[$pvt_s]['distance'] = $private_school['School']['distance'];
            }
        }
        //$allPublicSchools = $this->getSchoolSampleCode(urlencode($line1),urlencode($line2));
        //return $allPublicSchools;
        //if ($allPublicSchools['status']['code'] == 0) {
        //   if(!empty($allPublicSchools['property'][0]["school"])){

        if (!empty($psArray)) {

            $final_array = $psArray;
        } else {
            $final_array = $allPublicSchools['property'][0]["school"];
        }
        $index = 0;
        foreach ($final_array as $k => $schoolVal) {

            if (!isset($schoolVal['OBInstID'])) {
                continue;
            }
            $schoolDetails = $this->getPublicSchoolAddressById($schoolVal['OBInstID']);

            if ($schoolDetails['status']['code'] == 0) {
                $final_array[$k]['school_address']['locationaddress'] = $schoolDetails['school'][0]['SchoolProfileAndDistrictInfo']['SchoolLocation']['locationaddress'];
                $final_array[$k]['school_address']['locationcity'] = $schoolDetails['school'][0]['SchoolProfileAndDistrictInfo']['SchoolLocation']['locationcity'];
                $final_array[$k]['school_address']['stateabbrev'] = $schoolDetails['school'][0]['SchoolProfileAndDistrictInfo']['SchoolLocation']['stateabbrev'];
                $final_array[$k]['school_address']['ZIP'] = $schoolDetails['school'][0]['SchoolProfileAndDistrictInfo']['SchoolLocation']['ZIP'];
                $final_array[$k]["school_detail"] = $this->getSchoolDetail($schoolDetails);
            } else {
                $final_array[$k]['school_address']['locationaddress'] = '';
                $final_array[$k]['school_address']['locationcity'] = '';
                $final_array[$k]['school_address']['stateabbrev'] = '';
                $final_array[$k]['school_address']['ZIP'] = '';
            }


            //  }
            //}
        }
        return $final_array;
    }

    private function getSchoolSamplePrivateCode($lat, $long)
    {
        $url = $this->obapiurl . "/propertyapi/v1.0.0/school/snapshot?latitude=$lat&longitude=$long&radius=10&filetypetext=private&debug=True";
        return $this->curlPOIAPI($url);
    }

    private function getPublicSchoolAddressById($schoolID)
    {

        $url = $this->obapiurl . '/propertyapi/v1.0.0/school/detail?id=' . $schoolID . '&debug=True';
        return $this->curlPOIAPI($url);
    }

    private function getSchoolDetail($schoolDetails)
    {
        $schoolDetailData = array();
        if ($schoolDetails['status']['code'] == 0) {

            //Setting up the variable in pre-set array
            $SchoolProfileAndDistrictInfo = $schoolDetails['school'][0]['SchoolProfileAndDistrictInfo'];

            //School Name
            $schoolDetailData['school']['institutionname'] = $SchoolProfileAndDistrictInfo['SchoolSummary']['institutionname'];

            //Address
            $schoolDetailData['address']['locationaddress'] = $SchoolProfileAndDistrictInfo['SchoolLocation']['locationaddress'];
            $schoolDetailData['address']['locationcity'] = $SchoolProfileAndDistrictInfo['SchoolLocation']['locationcity'];
            $schoolDetailData['address']['stateabbrev'] = $SchoolProfileAndDistrictInfo['SchoolLocation']['stateabbrev'];
            $schoolDetailData['address']['ZIP'] = $SchoolProfileAndDistrictInfo['SchoolLocation']['ZIP'];

            //Contact
            $schoolDetailData['contact']['phone'] = $SchoolProfileAndDistrictInfo['SchoolContact']['phone'];

            //Website
            $schoolDetailData['website']['Websiteurl'] = $SchoolProfileAndDistrictInfo['SchoolContact']['Websiteurl'];

            //Technologymeasuretype
            $schoolDetailData['technology']['Technologymeasuretype'] = $SchoolProfileAndDistrictInfo['SchoolTech']['Technologymeasuretype'];

            //Special Eduction
            $schoolDetailData['eucation']['specialeducation'] = $SchoolProfileAndDistrictInfo['SchoolDetail']['specialeducation'];

            //No of Student
            $schoolDetailData['enrollment']['Studentsnumberof'] = $SchoolProfileAndDistrictInfo['SchoolEnrollment']['Studentsnumberof'];
            $schoolDetailData['enrollment']['Studentteacher'] = $SchoolProfileAndDistrictInfo['SchoolEnrollment']['Studentteacher'];

            //dates
            $schoolDetailData['dates']['startDate'] = $SchoolProfileAndDistrictInfo['DistrictSummary']['startDate'];
            $schoolDetailData['dates']['endDate'] = $SchoolProfileAndDistrictInfo['DistrictSummary']['endDate'];

            //Principle Name
            $schoolDetailData['principle']['Fullname'] = $SchoolProfileAndDistrictInfo['DistrictContact']['Prefixliteral'] . " " . $SchoolProfileAndDistrictInfo['DistrictContact']['Firstname'] . " " . $SchoolProfileAndDistrictInfo['DistrictContact']['Lastname'];
        }
        return $schoolDetailData;
    }

    public function school()
    {
        $context = array("title" => "My New Post", "body" => "This is my first post!");
        return view('schoolPartialView')->with("data", $context);
    }

    public function SendEmail(Request $request)
    {

        $propertiesAddresList = $request->input("addressList");
        $emailAdress = $request->input("email");
        $propertiesAddresList = json_decode($propertiesAddresList);
        $viewsArray = array();
        foreach ($propertiesAddresList as $address) {
            array_push($viewsArray, (String)$this->MailPartialView($address[0], $address[1]));
        }
        $completeView = (String)view('mail')->with("viewsArray", $viewsArray);

        return $this->sendMail($completeView, $emailAdress);
        return array("send");
    }

    private function MailPartialView($line1, $line2)
    {
        $result = $this->getallevent(urlencode($line1), urlencode($line2));
        $AVMResult = $this->getdetailmortgageowner(urlencode($line1), urlencode($line2));
        $psArray = array();
        foreach ($AVMResult["property"] as $key => $data) {
            $AssessmentHistory = $this->getAssessmentHistory($data["identifier"]["obPropId"]);
            if ($AssessmentHistory["property"] == null) {

            } else
                $psArray[$data["identifier"]["obPropId"]] = $AssessmentHistory["property"][0]["assessmenthistory"];
        }
        return view('mailPartial')->with('result', $result)->with("AVMResult", $AVMResult)->with("Assessment", $psArray);
    }

    private function getPropertyExtendDetail($line1, $line2)
    {
        $url = $this->obapiurl . '/propertyapi/v1.0.0/property/expandedprofile?address1=' . $line1 . '&address2=' . $line2 . '&debug=True';
        return $this->curlPOIAPI($url);
    }

    private function getSchoolSampleCode($add1 = null, $add2 = null)
    {
        $url = $this->obapiurl . '/propertyapi/v1.0.0/property/detailwithschools?address1=' . $add1 . '&address2=' . $add2 . '&debug=True';
        return $this->curlPOIAPI($url);
    }
}
