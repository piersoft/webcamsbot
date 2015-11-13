<?php
include('settings_t.php');
$text="torino";
$url ="http://nominatim.openstreetmap.org/search/".$text."/?format=xml&polygon=0&addressdetails=0";
//echo $url;
		$html = file_get_contents($url);
			echo $html;
//		$html=utf8_decode($html);
$doc = new DOMDocument;
$doc->loadHTML($html);
$xpa    = new DOMXPath($doc);

$lats   = $xpa->query('//place[1]/@lat');
$lons   = $xpa->query('//place[1]/@lon');

$lat="";
$lon="";
foreach($lats as $div) {
		$lat .= "\n".$div->nodeValue;
}
foreach($lons as $div) {
		$lon .= "\n".$div->nodeValue;
}
echo $lat.",".$lon;
?>
