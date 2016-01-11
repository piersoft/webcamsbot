<?php
include('settings_t.php');

$text="Milano";
$text=str_replace(" ","%20",$text);

	$url ="http://nominatim.openstreetmap.org/search/".$text."/?format=xml&polygon=0&addressdetails=0";

			$html = file_get_contents($url);
		//		$html=utf8_decode($html);

	$doc = new DOMDocument;
	$doc->loadHTML($html);
	$xpa    = new DOMXPath($doc);

	$lats   = $xpa->query('//place[1]/@lat');
	$lons   = $xpa->query('//place[1]/@lon');

	$lat="";
	$lon="";
	foreach($lats as $div) {
			$lat .= $div->nodeValue;
	}
	foreach($lons as $div) {
			$lon .= $div->nodeValue;
	}


	 $url ="http://api.webcams.travel/rest?method=wct.webcams.list_nearby&devid=".APIT."&lat=".$lat;
		$url .="&lng=".$lon."&unit=km&radius=".AROUND;
		$html = file_get_contents($url);
		echo $url;
	//		$html=utf8_decode($html);
			$doc = new DOMDocument;
			$doc->loadHTML($html);

			$xpa    = new DOMXPath($doc);
			$divs   = $xpa->query('//title');
			$divs1   = $xpa->query('//webcam/url');
			$divs2   = $xpa->query('//webcam/city');
			$divs3   = $xpa->query('//latitude');
			$divs4   = $xpa->query('//longitude');
			$divs5   = $xpa->query('//webcam/preview_url');
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
	if ($count ==0){
		echo "nessuna webcam";
	//	$content = array('chat_id' => $chat_id, 'text' => "Non ho trovato WebCams",'disable_web_page_preview'=>true);
	//	$telegram->sendMessage($content);
		exit;
	}
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
	for ($i=0;$i<$count;$i++){

			$ch = curl_init($diva5[$i]);
	//		array_push($urlfile,"img/temp".$i.".png");
			$urlfile[$i] ="img/temp".$i.".png";
			$fp = fopen($urlfile[$i], 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
		$alert1 = "\n".$diva[$i];
		$alert2 = "\nsita in ".$diva2[$i];
	$alert3 = " e aggiornata al ".date('d-m-Y H:i:s',$diva6[$i]);

 if ($diva1[$i]!=NULL){
//sleep(1);
 $longUrl = $diva1[$i];
 $apiKey = "AIzaSyC1XT5nGOhjGz6nJ74kyRT9hSdxsEACvHY";

 $postData = array('longUrl' => $longUrl, 'key' => $apiKey);
 $jsonData = json_encode($postData);

 $curlObj = curl_init();

 curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
 curl_setopt($curlObj, CURLOPT_HEADER, 0);
 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
 curl_setopt($curlObj, CURLOPT_POST, 1);
 curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

 $response = curl_exec($curlObj);

 // Change the response json string to object
 $json = json_decode($response);

 curl_close($curlObj);
 //  $reply="Puoi visualizzarlo su :\n".$json->id;
 $shortLink = get_object_vars($json);
	$alert4 = "\nvai su ".$shortLink['id'];
//	$alert4 = "\nvai su ".$longUrl;
 }
			if ($diva3[$i]!=NULL){
//sleep(1);
			$longUrl = "http://www.openstreetmap.org/?mlat=".$diva3[$i]."&mlon=".$diva4[$i]."#map=19/".$diva3[$i]."/".$diva4[$i];

				$apiKey = "AIzaSyC1XT5nGOhjGz6nJ74kyRT9hSdxsEACvHY";

				$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
				$jsonData = json_encode($postData);

				$curlObj = curl_init();

				curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
				curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curlObj, CURLOPT_HEADER, 0);
				curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
				curl_setopt($curlObj, CURLOPT_POST, 1);
				curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

				$response = curl_exec($curlObj);

				// Change the response json string to object
				$json = json_decode($response);

				curl_close($curlObj);
				//  $reply="Puoi visualizzarlo su :\n".$json->id;
				$shortLink = get_object_vars($json);
				//return $json->id;
						$alert5 ="\nMappa: ".$shortLink['id'];
	//  $alert5 ="\nMappa: ".$longUrl;
				}


				$alert6 = "\n\n___________\n\n";
			//	$img = curl_file_create($urlfile[$i],'image/png');
				echo $alert1.$alert2.$alert3.$alert4.$alert5.$alert6;
			//	$content = array('chat_id' => $chat_id, 'text' => $alert1.$alert2.$alert3.$alert4.$alert5.$alert6,'disable_web_page_preview'=>true);
			//	$contentp = array('chat_id' => $chat_id, 'photo' => $img);
			//	$telegram->sendPhoto($contentp);
			//	$telegram->sendMessage($content);


		}
			if ($count !=0){

	//	$content = array('chat_id' => $chat_id, 'text' => $comune.", trovate ".$count." webcams. Vedile tutte su mappa:\nhttp://www.piersoft.it/webcamsbot/?lat=".$lat."&lon=".$lon,'disable_web_page_preview'=>true);
	//	$telegram->sendMessage($content);
	}
?>
