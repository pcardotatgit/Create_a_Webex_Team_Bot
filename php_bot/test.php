<?php
/*
* Created By pcardot@cisco.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* open the [ debug.txt ] file in order to check dialog between this script and you bot
* Test the capability to send a message from your web server to your Webex Team Room
*/

$the_fichier="init_key_and_bot_id.txt";
$fd = @fopen($the_fichier,"r");

if (!$fd) die("Impossible d'ouvrir le fichier");
$ligne = fgets($fd, 300);	
LIST($mot,$the_room_id)=explode(":",$ligne);
echo 'Destination Room ID = '.$the_room_id;
$ligne = fgets($fd, 300);	
LIST($mot,$accesstoken)=explode(":",$ligne);
echo '<br><br>Bot Webex Token = '.$accesstoken;	
fclose($fd);

$file='debug.txt';
$fd = @fopen($file,"a+");
 
// Set HTTP POST headers
$headr = array();
$headr[] = 'Content-type: application/json';
$headr[] = 'Authorization: Bearer '.$accesstoken;

$replied_message="HELLO WORLD SENT FROM MY PHP SCRIPT";
echo '<br>Let me send an message to the Webex Team Room : '.$replied_message;

if($replied_message!="")
{
	fputs($fd,'message to send = ');
	fputs($fd,$replied_message); 
	fputs($fd,"\r\n"); 	
	//$url1 = "https://api.ciscospark.com/v1/messages";	  // old
	$url1 = "https://webexapis.com/v1/messages";	 
	$data = array("roomId" => $the_room_id, "markdown" => $replied_message);
	$data_string = json_encode($data);
	fputs($fd,'Reply orchestration to BOT: ');
	fputs($fd,$replied_message);
	fputs($fd,"\r\n");  
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
	curl_setopt($ch, CURLOPT_POST,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	curl_close($ch);
	fclose($fd);
	$replied_message="";
}

echo '<br><br>OK';

?>