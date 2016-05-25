<?php
error_reporting(1);
include("Class/db.class.php");
include("Class/user.class.php");
include("Class/room.class.php");
$db=new db();
$usersXML=$db->listall("user");
$room=new room();
$room->import("pelikaanhof.xml");
$room=$room->ReturnData();
//die();
foreach($usersXML as $user){
    $roomnumber=trim((string)$user->roomnumber);
    $room[$roomnumber]->userID=trim((string)$user->attributes());
    $vars=get_object_vars($room[$roomnumber]);
    foreach($vars as $property=>$value){
        echo $property." ".$value."<br>";
    }
    echo"<HR>";
    //$db->new_entry($room[$roomnumber]);
}
?>
