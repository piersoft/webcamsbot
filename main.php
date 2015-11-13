<?php
/**
* Telegram Bot example for Italian Museums of DBUnico Mibact Lic. CC-BY
* @author Francesco Piero Paolicelli @piersoft
*/
//include("settings_t.php");
include("Telegram.php");

class mainloop{
const MAX_LENGTH = 4096;
function start($telegram,$update)
{

	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
	//$data=new getdata();
	// Instances the class

	/* If you need to manually take some parameters
	*  $result = $telegram->getData();
	*  $text = $result["message"] ["text"];
	*  $chat_id = $result["message"] ["chat"]["id"];
	*/


	$text = $update["message"] ["text"];
	$chat_id = $update["message"] ["chat"]["id"];
	$user_id=$update["message"]["from"]["id"];
	$location=$update["message"]["location"];
	$reply_to_msg=$update["message"]["reply_to_message"];

	$this->shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg);
	$db = NULL;

}

//gestisce l'interfaccia utente
 function shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg)
{
	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");

	if ($text == "/start" || $text == "Info") {
		$reply = "Benvenuto. Per ricercare l'elenco delle Webcams attorno a te in un raggio di 15km, clicca sulla graffetta (📎) e poi 'posizione'. Verrà interrogato il DataBase di http://it.webcams.travel/ . In qualsiasi momento scrivendo /start ti ripeterò questo messaggio di benvenuto.\nRealizzato da @piersoft.";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$log=$today. ";new chat started;" .$chat_id. "\n";
		$this->create_keyboard($telegram,$chat_id);

	}elseif ($text == "Vicino a te" || $text == "/Vicino a te") {
		$reply = "Clicca sulla graffetta (📎) e poi 'posizione' ";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
} elseif ($text == "Più popolari" || $text == "/Più popolari") {
	$url ="http://api.webcams.travel/rest?method=wct.webcams.list_popular&devid=aa7e38066aa187539eaac34505d350db";

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
			$divs5   = $xpa->query('//webcam/thumbnail_url');
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
		//$alert = "\n".$diva5[$i];
//		$img = curl_file_create($urlfile[$i],'image/png');
		$alert1 = "\n".$diva[$i];
		$alert2 = "\nLocalità: ".$diva2[$i];
		$alert3 = "\nAggiornamento: ".date('d-m-Y H:i:s',$diva6[$i]);


if ($diva1[$i]!=NULL){
$longUrl = $diva1[$i];
$apiKey = API;

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
//	$alert .="\nMappa: ".$shortLink['id'];
	$alert4 = "\nVai alla WebCam: ".$shortLink['id'];
}
			if ($diva3[$i]!=NULL){
			//  $alert.= "\nCoordinate: ".$diva9[$i].",".$diva10[$i];

				$longUrl = "http://www.openstreetmap.org/?mlat=".$diva3[$i]."&mlon=".$diva4[$i]."#map=19/".$diva3[$i]."/".$diva4[$i];

				$apiKey = API;

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
				}
				$alert6 = "\n\n___________\n\n";
				$img = curl_file_create($urlfile[$i],'image/png');
				$content = array('chat_id' => $chat_id, 'text' => $alert1.$alert2.$alert3.$alert4.$alert5.$alert6,'disable_web_page_preview'=>true);
				$contentp = array('chat_id' => $chat_id, 'photo' => $img);
				$telegram->sendPhoto($contentp);
				$telegram->sendMessage($content);
		}

			$this->create_keyboard($telegram,$chat_id);
}
		//gestione segnalazioni georiferite
		elseif($location!=null)
		{

			$this->location_manager($telegram,$user_id,$chat_id,$location);
			exit;

		}
//elseif($text !=null)

		else{
		//	$text="torino";
	$text=str_replace(" ","%20",$text);

			$url ="http://nominatim.openstreetmap.org/search/".$text."/?format=xml&polygon=0&addressdetails=0";
			//echo $url;
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

	//		echo $lat.",".$lon;
			/*
			 $reply = "Hai selezionato un comando non previsto. Ricordati che devi prima inviare la tua posizione cliccando sulla graffetta (📎) ";
			 $content = array('chat_id' => $chat_id, 'text' => $reply);
			 $telegram->sendMessage($content);

			 $log=$today. ";wrong command sent;" .$chat_id. "\n";
			 */

			 $url ="http://api.webcams.travel/rest?method=wct.webcams.list_nearby&devid=".APIT."&lat=".$lat;
		  	$url .="&lng=".$lon."&unit=km&radius=".AROUND;

			//	$content = array('chat_id' => $chat_id, 'text' => $url,'disable_web_page_preview'=>true);

			//	$telegram->sendMessage($content);

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
		 			$divs5   = $xpa->query('//webcam/thumbnail_url');
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
		 		//$alert = "\n".$diva5[$i];
		 //		$img = curl_file_create($urlfile[$i],'image/png');
		 		$alert1 = "\n".$diva[$i];
		 		$alert2 = "\nLocalità: ".$diva2[$i];
		 		$alert3 = "\nAggiornamento: ".date('d-m-Y H:i:s',$diva6[$i]);


		 if ($diva1[$i]!=NULL){
		 $longUrl = $diva1[$i];
		 $apiKey = API;

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
		 //	$alert .="\nMappa: ".$shortLink['id'];
		 	$alert4 = "\nVai alla WebCam: ".$shortLink['id'];
		 }
		 			if ($diva3[$i]!=NULL){
		 			//  $alert.= "\nCoordinate: ".$diva9[$i].",".$diva10[$i];

		 				$longUrl = "http://www.openstreetmap.org/?mlat=".$diva3[$i]."&mlon=".$diva4[$i]."#map=19/".$diva3[$i]."/".$diva4[$i];

		 				$apiKey = API;

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
		 				}


		 			$alert6 = "\n\n___________\n\n";
		 				$img = curl_file_create($urlfile[$i],'image/png');
		 				$content = array('chat_id' => $chat_id, 'text' => $alert1.$alert2.$alert3.$alert4.$alert5.$alert6,'disable_web_page_preview'=>true);
		 				$contentp = array('chat_id' => $chat_id, 'photo' => $img);
		 				$telegram->sendPhoto($contentp);
		 				$telegram->sendMessage($content);


		 		}

			 $this->create_keyboard($telegram,$chat_id);


	}


}


// Crea la tastiera
function create_keyboard($telegram, $chat_id)
 {
//	 $forcehide=$telegram->buildKeyBoardHide(true);
//	 $content = array('chat_id' => $chat_id, 'text' => "Invia la tua posizione cliccando sulla graffetta (📎) in basso e, se vuoi, puoi cliccare due volte sulla mappa e spostare il Pin Rosso in un luogo specifico", 'reply_markup' =>$forcehide);
//	 $telegram->sendMessage($content);
	 $option = array(["Vicino a te","Più popolari"],["Info"]);
 	$keyb = $telegram->buildKeyBoard($option, $onetime=false);
 	$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[seleziona 'Più popolari', oppure per vedere le Webcam più vicine a te, clicca sulla graffetta \xF0\x9F\x93\x8E e poi 'posizione'. Infine se vuoi puoi digitare anche il nome della località ]");
 	$telegram->sendMessage($content);
 }




function location_manager($telegram,$user_id,$chat_id,$location)
	{

			$lon=$location["longitude"];
			$lat=$location["latitude"];
			$response=$telegram->getData();
			$count=0;
	 	$url ="http://api.webcams.travel/rest?method=wct.webcams.list_nearby&devid=".APIT."&lat=".$lat;
	 	$url .="&lng=".$lon."&unit=km&radius=".AROUND;

	 			$html = file_get_contents($url);
	 	//		$html=utf8_decode($html);
	 			$doc = new DOMDocument;
	 			$doc->loadHTML($html);

	 			$xpa    = new DOMXPath($doc);

	 	//	$count=3;
	 		//	echo $count;


	 			//echo $count;
	 			$divs   = $xpa->query('//title');
	 			$divs1   = $xpa->query('//webcam/url');
	 			$divs2   = $xpa->query('//webcam/city');
	 			$divs3   = $xpa->query('//latitude');
	 			$divs4   = $xpa->query('//longitude');
	 			$divs5   = $xpa->query('//webcam/thumbnail_url');
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
	  	//$alert = "\n".$diva5[$i];
	//		$img = curl_file_create($urlfile[$i],'image/png');
	 		$alert1 = "\n".$diva[$i];
	 		$alert2 = "\nLocalità: ".$diva2[$i];
	 		$alert3 = "\nAggiornamento: ".date('d-m-Y H:i:s',$diva6[$i]);


if ($diva1[$i]!=NULL){
	$longUrl = $diva1[$i];
	$apiKey = API;

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
//	$alert .="\nMappa: ".$shortLink['id'];
		$alert4 = "\nVai alla WebCam: ".$shortLink['id'];
}
	 	  	if ($diva3[$i]!=NULL){
	 	  	//  $alert.= "\nCoordinate: ".$diva9[$i].",".$diva10[$i];

	 	  	  $longUrl = "http://www.openstreetmap.org/?mlat=".$diva3[$i]."&mlon=".$diva4[$i]."#map=19/".$diva3[$i]."/".$diva4[$i];

	 	  	  $apiKey = API;

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
	 	  	  }
	 				$alert6 = "\n\n___________\n\n";
					$img = curl_file_create($urlfile[$i],'image/png');
					$content = array('chat_id' => $chat_id, 'text' => $alert1.$alert2.$alert3.$alert4.$alert5.$alert6,'disable_web_page_preview'=>true);
					$contentp = array('chat_id' => $chat_id, 'photo' => $img);
		$telegram->sendPhoto($contentp);
					$telegram->sendMessage($content);


	 	  }

/*
			$chunks = str_split($alert, self::MAX_LENGTH);
		//	$chunksp = str_split($urlfile, self::MAX_LENGTH);
				$p=0;
				foreach($chunks as $chunk) {
		    $forcehide=$telegram->buildForceReply(true);
				if (strpos($chunk,'jpg') !== false){
					$img = curl_file_create($urlfile[$p],'image/png');
					$content1 = array('chat_id' => $chat_id, 'photo' => $img);
					$telegram->sendPhoto($content1);
					$p++;
				}
				$content = array('chat_id' => $chat_id, 'text' => $chunk." ".$urlfile[$p],'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);

			}
*/
				$this->create_keyboard($telegram,$chat_id);

	//			$content = array('chat_id' => $chat_id, 'text' => "Invia la tua posizione tramite la graffetta (📎)");
	//				$telegram->sendMessage($content);
	}


}

?>
