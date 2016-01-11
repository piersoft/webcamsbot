<?php
/**
* Telegram Bot example for Webcams Travel (c)
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
		$reply = "Benvenuto. Per ricercare l'elenco delle Webcams attorno a te in un raggio di 15km, clicca sulla graffetta (📎) e poi 'posizione'. Verrà interrogato il DataBase di http://it.webcams.travel/ . Puoi anche digitare direttamente il nome della Città. In qualsiasi momento scrivendo /start ti ripeterò questo messaggio di benvenuto.\nRealizzato da @piersoft. Geocoding tramite openstreetmap.org lic. odbl";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$log=$today. ";new chat started;" .$chat_id. "\n";
		$this->create_keyboard($telegram,$chat_id);

	}elseif ($text == "Vicino a te" || $text == "/Near you") {
		$reply = "Clicca sulla graffetta (📎) e poi 'posizione' ";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
} elseif ($text == "Più popolari" || $text == "/Popular") {
	$useragent=$_SERVER['HTTP_USER_AGENT'];
$mobile=0;
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
	$mobile=1;
}else $mobile=0;

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
	if ($count ==0){
		$content = array('chat_id' => $chat_id, 'text' => "Non ho trovato WebCams",'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
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
		//$alert = "\n".$diva5[$i];
//		$img = curl_file_create($urlfile[$i],'image/png');
		$alert1 = "\n".$diva[$i];
		$alert2 = "\nLocalità: ".$diva2[$i];
		$alert3 = "\nAggiornamento: ".date('d-m-Y H:i:s',$diva6[$i]);


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
//return $json->id;
//	$alert .="\nMappa: ".$shortLink['id'];
	$alert4 = "\nVai alla WebCam: ".$shortLink['id'];
//	$alert4 = "\nVai alla WebCam: ".$longUrl;
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

	//					$alert5 ="\nMappa: ".$longUrl;
				}
				$alert6 = "\n\n___________\n\n";
				$img = curl_file_create($urlfile[$i],'image/png');
				$content = array('chat_id' => $chat_id, 'text' => $alert1.$alert2.$alert3.$alert4.$alert5.$alert6,'disable_web_page_preview'=>true);
				$contentp = array('chat_id' => $chat_id, 'photo' => $img);
				$telegram->sendPhoto($contentp);
				$telegram->sendMessage($content);
		}

			$this->create_keyboard($telegram,$chat_id);
				exit;
}
		//gestione segnalazioni georiferite
		elseif($location!=null)
		{

			$this->location_manager($telegram,$user_id,$chat_id,$location);
			exit;

		}

		else{
		//	$text="torino";
		$comune=$text;
		$location="Sto cercando le WebCams nel Comune di: ".$comune;
		$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
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


			 $url ="http://api.webcams.travel/rest?method=wct.webcams.list_nearby&devid=".APIT."&lat=".$lat;
		  	$url .="&lng=".$lon."&unit=km&radius=".AROUND;
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
				$content = array('chat_id' => $chat_id, 'text' => "Non ho trovato WebCams",'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
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
		 				$img = curl_file_create($urlfile[$i],'image/png');
		 				$content = array('chat_id' => $chat_id, 'text' => $alert1.$alert2.$alert3.$alert4.$alert5.$alert6,'disable_web_page_preview'=>true);
		 				$contentp = array('chat_id' => $chat_id, 'photo' => $img);
		 				$telegram->sendPhoto($contentp);
		 				$telegram->sendMessage($content);


		 		}
					if ($count !=0){

				$content = array('chat_id' => $chat_id, 'text' => $comune.", trovate ".$count." webcams. Vedile tutte su mappa:\nhttp://www.piersoft.it/webcamsbot/?lat=".$lat."&lon=".$lon,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
			}
			 $this->create_keyboard($telegram,$chat_id);

	}


}


// Crea la tastiera
function create_keyboard($telegram, $chat_id)
 {
	 $option = array(["Vicino a te","Più popolari"],["Info"]);
 	$keyb = $telegram->buildKeyBoard($option, $onetime=false);
 	$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[seleziona la tua scelta oppure, per vedere le Webcam più vicine a te, clicca sulla graffetta \xF0\x9F\x93\x8E e quindi 'posizione']");
 	$telegram->sendMessage($content);
 }



function location_manager($telegram,$user_id,$chat_id,$location)
	{
		$useragent=$_SERVER['HTTP_USER_AGENT'];
	$mobile=0;
	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
		$mobile=1;
	}else $mobile=0;
			$lon=$location["longitude"];
			$lat=$location["latitude"];
			$response=$telegram->getData();
			$reply="http://nominatim.openstreetmap.org/reverse?email=piersoft2@gmail.com&format=json&lat=".$lat."&lon=".$lon."&zoom=18&addressdetails=1";
			$json_string = file_get_contents($reply);
			$parsed_json = json_decode($json_string);
			//var_dump($parsed_json);
			$comune="";
			$temp_c1 =$parsed_json->{'display_name'};

			if ($parsed_json->{'address'}->{'town'}) {
				$temp_c1 .="\nCittà: ".$parsed_json->{'address'}->{'town'};
				$comune .=$parsed_json->{'address'}->{'town'};
			}else 	$comune .=$parsed_json->{'address'}->{'city'};

			if ($parsed_json->{'address'}->{'village'}) $comune .=$parsed_json->{'address'}->{'village'};
			$location="Sto cercando le WebCams nel Comune di: ".$comune." tramite le coordinate che hai inviato: ".$lat.",".$lon;
			$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
sleep(1);
			$alert="";
		//	echo $comune;

			$count=0;
	 	$url ="http://api.webcams.travel/rest?method=wct.webcams.list_nearby&devid=".APIT."&lat=".$lat;
	 	$url .="&lng=".$lon."&unit=km&radius=".AROUND;

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
		if ($count ==0){
			$content = array('chat_id' => $chat_id, 'text' => "Non ho trovato WebCams",'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
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
	$shortLink = get_object_vars($json);
//	$alert4 = "\nvai su ".$shortLink['id'];
	$alert4 = "\nvai su ".$longUrl;
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
	 	  	  $shortLink = get_object_vars($json);
	 		  //		$alert5 ="\nMappa: ".$shortLink['id'];
        $alert5 ="\nMappa: ".$longUrl;
	 	  	  }
	 				$alert6 = "\n\n___________\n\n";
					$img = curl_file_create($urlfile[$i],'image/png');
					$content = array('chat_id' => $chat_id, 'text' => $alert1.$alert2.$alert3.$alert4.$alert5.$alert6,'disable_web_page_preview'=>true);
					$contentp = array('chat_id' => $chat_id, 'photo' => $img);
		$telegram->sendPhoto($contentp);
					$telegram->sendMessage($content);


	 	  }
				if ($count !=0){
			$content = array('chat_id' => $chat_id, 'text' => $comune.", trovate ".$count." webcams. Vedile tutte su mappa:\nhttp://www.piersoft.it/webcamsbot/?lat=".$lat."&lon=".$lon,'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
		}
				$this->create_keyboard($telegram,$chat_id);

	}


}

?>
