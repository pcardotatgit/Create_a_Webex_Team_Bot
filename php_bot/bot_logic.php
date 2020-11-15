<?php
/*
* Created By pcardot@cisco.  from an example created by dhenwood@cisco.com
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* The goal is to make people familiar with Webex Team Bot and how they work
*
* open the [ debug.txt ] file in order to see the details of the dialog between this script and your Webex Team Bot
*/

//error STOP uncomment here to stop the script in case of a loop
$the_fichier="init_key_and_bot_id.txt";
$fd = @fopen($the_fichier,"r");
if (!$fd) die("the init_key_and_bot_id.txt file can't be openned");
$ligne = fgets($fd, 300);	
LIST($mot,$the_room_id)=explode(":",$ligne);
echo 'Destination Room ID = '.$the_room_id;
$ligne = fgets($fd, 300);	
LIST($mot,$accesstoken)=explode(":",$ligne);
echo '<br><br>Bot Webex Token = '.$accesstoken;	
fclose($fd);

$file='debug.txt';
$fd = @fopen($file,"a+");
// Get Webhook POST data and extract Webhook ID
$postdata = file_get_contents("php://input");
$jsonPost = json_decode($postdata,true);
$jsonData = $jsonPost["data"]["id"];
 
// Set variables. The accesstoken is taken from developer.ciscospark.com
//$url = 'https://api.ciscospark.com/v1/messages/'.$jsonData; // old
$url = "https://webexapis.com/v1/messages/".$jsonData;	
fputs($fd,"========================================================================================");
fputs($fd,"\r\n");
fputs($fd,date('j/m/y H:i:s'));
fputs($fd,"\r\n");
fputs($fd,"STEP 1 - Ok a message was received into the webex bot team room. Message ID is :");
fputs($fd,"\r\n");
fputs($fd,"\r\n");
fputs($fd,"		".$jsonData); 
fputs($fd,"\r\n"); 
fputs($fd,"\r\n");
fputs($fd,"STEP 2 - Build an HTTP header");
fputs($fd,"\r\n");
// Set HTTP POST headers
$headr = array();
$headr[] = 'Content-type: application/json';
$headr[] = 'Authorization: Bearer '.$accesstoken;
fputs($fd,"		OK DONE header is : ");
fputs($fd,"\r\n");
fputs($fd,"\r\n");
fputs($fd,"		'Content-type': application/json");
fputs($fd,"\r\n");
fputs($fd,"		'Authorization': Bearer ".$accesstoken);
fputs($fd,"\r\n"); 
fputs($fd,"STEP 3 - Then get the sent message - Send a HTTP GET MESSAGE"); 
fputs($fd,"\r\n"); 
fputs($fd,"\r\n");
fputs($fd,"		API Call is :"); 
fputs($fd,"\r\n"); 
fputs($fd,"\r\n");
fputs($fd,"		".$url); 
fputs($fd,"\r\n"); 
fputs($fd,"\r\n");
echo 'GET SENT MESSAGE<br>'; 
// Send HTTP GET to obtain text message
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
curl_setopt($ch, CURLOPT_HTTPGET,true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
curl_close($ch);
fputs($fd,"		OK Done JSON result is : ");
fputs($fd,"=>\r\n"); 
fputs($fd,"\r\n");
fputs($fd,$response);
fputs($fd,"<=\r\n"); 
fputs($fd,"\r\n");


if (stripos($response,'"errors":') != 0)
{
	fputs($fd,'ERROR = ');
	fputs($fd,"\r\n");
	fputs($fd,$response); 
	fputs($fd,"\r\n"); 	
	fputs($fd,"\r\n"); 	
	echo 'ERROR MESSAGE RECEIVED FROM WEBEX<br>';
	echo '<b>'.$response.'/<b>';
	$replied_message='-bot: HELLO WORLD !';
	echo '<h2>TRYING TO SEND A TEST MESSAGE :'.$replied_message.'</h2>';	
	
	$url1 = "https://webexapis.com/v1/messages";	 
	$data = array("roomId" => $the_room_id, "markdown" => $replied_message);
	$data_string = json_encode($data);
	fputs($fd,'Reply orchestration to BOT: ');
	fputs($fd,$replied_message);
	fputs($fd,"\r\n");  
	fputs($fd,"room id =".$the_room_id);
	fputs($fd,"\r\n");  	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
	curl_setopt($ch, CURLOPT_POST,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);	
	echo '<h2>DID YOU RECEIVED IT IN THE WEBEX TEAM ROOM ?</h2>';
	die();
	
}

// Extract the text message and who posted the message
echo 'extract MESSAGE<br>';
fputs($fd,'		Let\'s extract the MESSAGE . The message is :');
fputs($fd,"\r\n");
$result = json_decode($response);
$messageData = trim($result->{'text'});
$messageUser = $result->{'personEmail'};
echo 'MESSAGE : '.$messageData.'<br>';
fputs($fd,"\r\n"); 
fputs($fd,"\r\n");  
fputs($fd,"		=> \r\n");
fputs($fd,"			".$messageData);
fputs($fd,"\r\n		<=");
fputs($fd,"\r\n");
fputs($fd,'Step 4 - Let\'s parse the MESSAGE . And send back a message ');
fputs($fd,"\r\n");
fputs($fd,"\r\n");

if(stripos($messageData,"bot:")==false)
{
	// Define a string we will listen for and do something with
	if(($messageData=="ping")&&(stripos($messageData,"bot:")==false))
	{
		fputs($fd,'		message = ping : We are In the "ping" if branch');
		fputs($fd,"\r\n"); 
		$replied_message="-bot: => Yeah !! I received your ping message !";
		fputs($fd,'		Replied Message will be : "bot: => Yeah !! I received your ping message !"');
		fputs($fd,"\r\n"); 	
	}
	else if((stripos($messageData,"ping")!=false)&&(stripos($messageData,"bot:")!=false))
	{
		$replied_message="-bot: PONG : [ Action for the BOT. An example of hyper link ](http://www.google.com)";
	}
	else if ((stripos($messageData,"hello")!=false)&&(stripos($messageData,"bot:")==false))
	{
		$replied_message="-bot: Hi Man, How are you ?";
	}
	else if ((stripos($messageData,"m fine")!=false)&&(stripos($messageData,"bot:")!=false))
	{
		$replied_message="Perfect !";
	}
	else if ($messageData === "-bot: => I don't understand this")
	{
		$replied_message="";
	}
	else if ($messageData === "")
	{
		$replied_message="-bot: PONG !";
	}
	else if ($messageData === "PONG !")
	{
		$replied_message="";
	}
	else 
	{
		$replied_message="-bot: I don't understand this";
	}
}
else
{	
	fputs($fd,"\r\n"); 	
	fputs($fd,'		This message is an answer from the BOT.  Don\'t process it ');
	fputs($fd,"\r\n"); 	
	fputs($fd,"\r\n"); 	
}
if($replied_message!="")
{
	fputs($fd,"\r\n"); 	
	fputs($fd,"\r\n"); 	
	fputs($fd,'		message to send = ');
	fputs($fd,"\r\n"); 	
	fputs($fd,"\r\n"); 	
	//$url1 = "https://api.ciscospark.com/v1/messages";	  // old
	$url1 = "https://webexapis.com/v1/messages";	 
	$data = array("roomId" => $the_room_id, "markdown" => $replied_message);
	$data_string = json_encode($data);
	fputs($fd,"		".$replied_message);
	fputs($fd,"\r\n");  
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
	curl_setopt($ch, CURLOPT_POST,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);
	fputs($fd,"\r\n"); 	
	fputs($fd,"\r\n"); 	
	fputs($fd,'		OK Message Sent to webex team room ');
	fputs($fd,"\r\n"); 	
	fputs($fd,'		'.$response);
	fputs($fd,"\r\n"); 		
	fclose($fd);
	$replied_message="";
}

echo 'OK end of script';

?>