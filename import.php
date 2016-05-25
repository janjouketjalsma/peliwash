<?php
//Import roomnumbers from a CSV file
error_reporting(E_ALL);
include("Class/user.class.php");
include("Class/sqldb.class.php");
$file=file("Woonruimtes.csv");
$db=new db();
$i=0;
foreach($file as $kamernummer){
	$info=explode(" ",$kamernummer);
	$nieuwnummer=$info[1]." K".$info[3];
	$emptyuser=new user();
	$emptyuser->roomnumber=$kamernummer;
	$emptyuser->gencode();
	$emptyuser->setID(str_pad($i, 3, '0', STR_PAD_LEFT));
	//$db->new_entry($emptyuser); //Uncomment this line to make the script active!
	$i++;
}
echo "Done";
?>
