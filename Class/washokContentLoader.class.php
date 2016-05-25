<?php

class washokContentLoader {
    public function __construct(){
		//error_reporting(E_ALL);
        $content[]= "Je kunt hier zien wie er aan de beurt is te wassen.";
		$content[]= "Doordat je online reserveert voor het wassen, <br>is je wasbeurt veel beter in te plannen";
		$content[]= "Activeer je account vandaag nog om te kunnen reserveren";
		$content[]= "Blijf op de hoogte met Journaal24 tijdens de was!";
		$content[]= "Nu online reserveren!";
		
		if(!isset($content[$_GET["q"]])){
			echo "";
		}else{
			echo "<br><br><br>".$content[$_GET["q"]];
		}
		file_put_contents("ip.txt",$this->getRealIpAddr());
    }
	public function getRealIpAddr(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}

?>
