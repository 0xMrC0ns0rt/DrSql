<?php
/*

   ____  _____ ___    __  ______       __  _____  ____  _____    __  _____  ______    ____ 
  / __ \/ ___//   |  /  |/  /   |     /  |/  / / / / / / /   |  /  |/  /  |/  /   |  / __ \
 / / / /\__ \/ /| | / /|_/ / /| |    / /|_/ / / / / /_/ / /| | / /|_/ / /|_/ / /| | / / / /
/ /_/ /___/ / ___ |/ /  / / ___ |   / /  / / /_/ / __  / ___ |/ /  / / /  / / ___ |/ /_/ / 
\____//____/_/  |_/_/  /_/_/  |_|  /_/  /_/\____/_/ /_/_/  |_/_/  /_/_/  /_/_/  |_/_____/  
                                                                                           -SOCIETY CONTRIBUTIONS
Dr SQL project (C) 2023.
Written by Osama Muhammad as a society contribution.
This code is fully free & open source, you can re-publish this resource without changing the copyrights.
You can develop this resource and add your credits to this work, but never remove the original core credits.
*/
session_start();
#CONFIGURATION\
$_SESSION['directBlock'] = 'on'; //on or off: this will block the request and end all processes once an injection try is detected.
$checkWithoutRequire = 'on'; //on or off: this will always check for user's submits even without calling the check function.
$cloudjetFirewall = 'on'; //on or off: this option enables the Cloudjet Firewall to prevent bots from accessing your server.
$_SESSION['cloudjetApiKey'] = 'CLOUDJET_APIKEY_XXXXXXXXXX'; //your Cloudjet Firewall API key, only required if you've enabled CloudjetFirewall option.
$_SESSION['cloudjetBlockMsg'] = 'You do not have access to this page.'; //the message to be displayed to blocked users by Cloudjet Firewall.
$_SESSION['logBannedBots'] = 'on'; //on or off: this will log all banned bots in banned.txt file.

function omCloudjetFirewallCheck($ip){
$apikey = $_SESSION['cloudjetApiKey'];
$url = "https://apis.cloudjet.org/firewall.php?apikey={$apikey}&ip={$ip}";
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "cache-control: no-cache"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);



if(json_decode($response)->status == "error"){
    echo json_decode($response)->message;exit;
    
}
else{
    if(json_decode($response)->isbot == "yes"){
      if($_SESSION['logBannedBots'] == 'on'){
        
$filename = 'banned.txt';
$line_to_add = 'Bot: '.$ip;

if (file_exists($filename)) {

    $file = fopen($filename, 'a');
    fwrite($file, $line_to_add . "\n");
    fclose($file);
} else {
   
    $file = fopen($filename, 'w');
    fwrite($file, $line_to_add . "\n");
    fclose($file);
}

      }
  echo $_SESSION['cloudjetBlockMsg'];exit;
    
}
}

}
function omCheckForInject($string){
	if (strpos($string, "'") !== false || strpos($string, '"') !== false) {
		return 1;
	}
}
//check all of the get params
function omGetCheck(){
	if(!empty($_GET)){
		$_omTries['get'] = array();
		foreach($_GET as $gparam=>$gparamvalue){
			if((omCheckForInject($gparamvalue)) == 1){
				$arrToPush = array($gparamvalue);
				array_push($_omTries['get'], $gparamvalue);
			}
		}
	}
		else{
			$_omTries['get'] = array();
		}
		return $_omTries['get'];
	}
//check all of the post params
function omPostCheck(){
	if(!empty($_POST)){
		$_omTries['post'] = array();
		foreach($_POST as $pparam=>$pparamvalue){
			if((omCheckForInject($pparamvalue)) == 1){
				$arrToPush = array($pparamvalue);
				array_push($_omTries['post'], $pparamvalue);
			}
		}
	}
	else{
		$_omTries['post'] = array();
	}
	return $_omTries['post'];
}
function omCheck($get,$post){
	$directBlock = $_SESSION['directBlock'];
	switch ($get){
		case 0:
			$cg = array();
		case 1:
			$cg = omGetCheck();
		}
	switch ($post){
		case 0:
			$cp = array();
		case 1:
			$cp = omPostCheck();
		}
	if(count($cp)>0 || count($cg)>0){
		$result = [
		'block' => 'true',
		'tries' => [
			'GetInjectionsCount'=>count($cg),
			'PostInjectionsCount'=>count($cp),
			'GetInjections'=>$cg,
			'PostInjections'=>$cp
		]
		];
		if(strtolower($directBlock) == 'on'){
			echo '<h1><center>An injection try has been detected.<br><u>PROTECTED BY DR SQL PROJECT BY OSAMA MUHAMMAD</u></center></h1>';exit;
		}
	else
		return $result;
	}
	}
if(strtolower($cloudjetFirewall) == 'on'){
  $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
  omCloudjetFirewallCheck($ip);
}
if(strtolower($checkWithoutRequire) == 'on'){
	$directBlock = $_SESSION['directBlock'];
	omCheck($directBlock,1,1);
}
?>
