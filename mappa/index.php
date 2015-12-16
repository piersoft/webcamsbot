<?php
include('settings_t.php'); // oppure definire APIT con l'api 
$useragent=$_SERVER['HTTP_USER_AGENT'];
$mobile=0;
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
$mobile=1;
}else $mobile=0;

$lat=$_GET["lat"];
$lon=$_GET["lon"];

$count=0;
$url ="http://api.webcams.travel/rest?method=wct.webcams.list_nearby&devid=".APIT."&lat=".$lat;
$url .="&lng=".$lon."&unit=km&radius=".AROUND;

//echo $url;
	$html = file_get_contents($url);
//		$html=utf8_decode($html);
	$doc = new DOMDocument;
	$doc->loadHTML($html);

	$xpa    = new DOMXPath($doc);

	$divs   = $xpa->query('//title');
	$divs1   = $xpa->query('//webcam/url');
	$divs2   = $xpa->query('//webcam/city');
	$divs3   = $xpa->query('//latitude');
	$divs4   = $xpa->query('//longitude');
	if($mobile==1){
			$divs5   = $xpa->query('//webcam/thumbnail_url');
	}	else 	$divs5   = $xpa->query('//webcam/preview_url');
	$divs6   = $xpa->query('//last_update');

	$diva=[];
	$diva1=[];
	$diva2=[];
	$diva3=[];
	$diva4=[];
	$diva5=[];
	$diva6=[];
foreach($divs as $div) {
$count++;

		array_push($diva,$div->nodeValue);
}
echo $count;
	foreach($divs1 as $div1) {

				array_push($diva1,$div1->nodeValue);
	}

	foreach($divs2 as $div2) {

				array_push($diva2,$div2->nodeValue);
	}
	foreach($divs3 as $div3) {
			$allerta3 .= "\n<br>".$div3->nodeValue;
				array_push($diva3,$div3->nodeValue);
	}
	foreach($divs4 as $div4) {

				array_push($diva4,$div4->nodeValue);
	}
	foreach($divs5 as $div5) {

				array_push($diva5,$div5->nodeValue);
	}
	foreach($divs6 as $div6) {

				array_push($diva6,$div6->nodeValue);
	}

	$urlfile=[];
		$features=[];
		$original_data = json_decode($json, true);
		$features = array();

		for ($i=0;$i<$count;$i++){



	//	foreach($original_data as $key => $value) {

				$features[] = array(
								'type' => 'Feature',
								'geometry' => array('type' => 'Point', 'coordinates' => array((float)$diva4[$i],(float)$diva3[$i])),
								'properties' => array('name' => $diva[$i], 'url' => $diva1[$i], 'preview' => $diva5[$i]),
								);


}
		$allfeatures = array('type' => 'FeatureCollection', 'features' => $features);
	//	echo json_encode($allfeatures, JSON_PRETTY_PRINT);

		$file1 = "mappaf.geojson";
		$dest1 = fopen($file1, 'w');

		$geostring=json_encode($allfeatures, JSON_PRETTY_PRINT);

		fputs($dest1, $geostring);
		fclose($file1);
?>

<!DOCTYPE html>
<html lang="it">
  <head>
  <title>Webcams Travel by Bot</title>
  <link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.css" />
        <link rel="stylesheet" href="MarkerCluster.css" />
        <link rel="stylesheet" href="MarkerCluster.Default.css" />
        <meta property="og:image" content="http://www.piersoft.it/webcamsbot/webcamstravels.jpg"/>
  <script src="http://cdn.leafletjs.com/leaflet-0.7.5/leaflet.js"></script>
   <script src="leaflet.markercluster.js"></script>
<script type="text/javascript">

function microAjax(B,A){this.bindFunction=function(E,D){return function(){return E.apply(D,[D])}};this.stateChange=function(D){if(this.request.readyState==4 ){this.callbackFunction(this.request.responseText)}};this.getRequest=function(){if(window.ActiveXObject){return new ActiveXObject("Microsoft.XMLHTTP")}else { if(window.XMLHttpRequest){return new XMLHttpRequest()}}return false};this.postBody=(arguments[2]||"");this.callbackFunction=A;this.url=B;this.request=this.getRequest();if(this.request){var C=this.request;C.onreadystatechange=this.bindFunction(this.stateChange,this);if(this.postBody!==""){C.open("POST",B,true);C.setRequestHeader("X-Requested-With","XMLHttpRequest");C.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");C.setRequestHeader("Connection","close")}else{C.open("GET",B,true)}C.send(this.postBody)}};

</script>
  <style>
  #mapdiv{
        position:fixed;
        top:0;
        right:0;
        left:0;
        bottom:0;
}
#infodiv{
background-color: rgba(255, 255, 255, 0.95);

font-family: Helvetica, Arial, Sans-Serif;
padding: 2px;


font-size: 10px;
bottom: 13px;
left:0px;


max-height: 50px;

position: fixed;

overflow-y: auto;
overflow-x: hidden;
}
#loader {
    position:absolute; top:0; bottom:0; width:100%;
    background:rgba(255, 255, 255, 1);
    transition:background 1s ease-out;
    -webkit-transition:background 1s ease-out;
}
#loader.done {
    background:rgba(255, 255, 255, 0);
}
#loader.hide {
    display:none;
}
#loader .message {
    position:absolute;
    left:50%;
    top:50%;
}
</style>
  </head>

<body>

  <div data-tap-disabled="true">

  <div id="mapdiv"></div>
<div id="infodiv" style="leaflet-popup-content-wrapper">
  <p><b>Database WebCamsTravel powered by https://telegram.me/webcamstravelbot @piersoft<br></b>
</div>
<div id='loader'><span class='message'>loading</span></div>
</div>
  <script type="text/javascript">
	//	var lat=41.1181,
  //      lon=16.8695,
var  lat = parseFloat('<?php printf($_GET['lat']); ?>');
var  lon = parseFloat('<?php printf($_GET['lon']); ?>');
      var  zoom=14;
        var osm = new L.TileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {maxZoom: 20, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
		var mapquest = new L.TileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png', {subdomains: '1234', maxZoom: 18, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});

        var map = new L.Map('mapdiv', {
                    editInOSMControl: true,
            editInOSMControlOptions: {
                position: "topright"

            },
            center: new L.LatLng(lat, lon),
            zoom: zoom,
            layers: [osm]
        });

        var baseMaps = {
    "Mapnik": osm,
    "Mapquest Open": mapquest
        };
        L.control.layers(baseMaps).addTo(map);

       var ico=L.icon({iconUrl:'cctv.png', iconSize:[40,45],iconAnchor:[20,0]});
       var markers = L.markerClusterGroup();
       //({spiderfyOnMaxZoom: true, showCoverageOnHover: true,zoomToBoundsOnClick: true});

        function loadLayer(url)
        {
                var myLayer = L.geoJson(url,{
                        onEachFeature:function onEachFeature(feature, layer) {
                                if (feature.properties && feature.properties.name) {
                                    var text = "<p>"+feature.properties.name+"</p>";

                                }

                        },
                        pointToLayer: function (feature, latlng) {
                        var marker = new L.Marker(latlng, { icon: ico });

                        markers[feature.properties.name] = marker;
                        marker.bindPopup('<img src="http://www.piersoft.it/webcamsbot/ajax-loader.gif">',{maxWidth:200, autoPan:true});
                        marker.bindPopup('<div>'+feature.properties.name+'</br><img src=\"'+feature.properties.preview+'\" style=\"width: 160px; height: 100px;\" ></br><a href=\"'+feature.properties.url+'\" />Vai alla web cam</a>',{maxWidth:200, autoPan:true});

                      //  marker.on('click',showMarker());
                        return marker;
                        }
                });
                //.addTo(map);

                markers.addLayer(myLayer);
                map.addLayer(markers);
              //  markers.on('click',showMarker);
        }

microAjax('mappaf.geojson',function (res) {
var feat=JSON.parse(res);
loadLayer(feat);
  finishedLoading();
} );

function showMarker(marker){

  var text = "<p>"+feature.properties.name+"</p>";
  marker.layer.closePopup();
  marker.layer.bindPopup(text);
  marker.layer.openPopup();

}

function startLoading() {
    loader.className = '';
}

function finishedLoading() {
    // first, toggle the class 'done', which makes the loading screen
    // fade out
    loader.className = 'done';
    setTimeout(function() {
        // then, after a half-second, add the class 'hide', which hides
        // it completely and ensures that the user can interact with the
        // map again.
        loader.className = 'hide';
    }, 500);
}
</script>

</body>
</html>
