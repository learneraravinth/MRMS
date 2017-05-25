<?php
    // this is weebhook verification
	/* $challenge = $_REQUEST['hub_challenge'];
	$verify_token = $_REQUEST['hub_verify_token'];

		if ($verify_token === 'KC_ME') {
		  echo $challenge;
		} */
   // Receiving data
	/* $input = json_decode(file_get_contents('php://input'), true);
 */
	$senderid = 100001979366684;
	$message  = 'hai thi is aravinth';
 
  $page_access_token="EAAXrDPtqFaEBADsZC17syeoltPGXxlvvLb4rmsVf3GTXwoDHyZBQdiQiuqRhKZAqpRajDxX3HJMUBC52iEcIWlEJ4J3GL3xtDOIBabZBHUubtrjsJNwaymHCOXAI6DVjUl6qGWYprtBpiBh79vAAZB5pUiTXjWDCHNKUnFDgxckrXsJw5jEzT6y8aTVctZBykZD" // palce your accesstoken
  
 //API Url
$url = "https://graph.facebook.com/v2.6/me/messages?access_token=$page_access_token";

//The JSON data of message (only text message).
$jsonData = '{
    "recipient":{
        "id":"'.$senderid.'"
    }, 
    "message":{
        "text":"Hey! This is SRINU how can i help you"
    }
}';


//call to send message function

  send_message($jsonData,$url);

function send_message($jsonData,$url){
	
			//Encode the array into JSON.
			$jsonDataEncoded = $jsonData;

			//Initiate cURL.
			$ch = curl_init($url);
			
			//Tell cURL that we want to send a POST request.
			curl_setopt($ch, CURLOPT_POST, 1);

			//Attach our encoded JSON string to the POST fields.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

			//Set the content type to application/json
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

			//Execute the request
			  $result = curl_exec($ch);
			
 }
?>
