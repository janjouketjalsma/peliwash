<?php
//Default page template to load
$GLOBALS["DEFAULT_PAGE"]="home";
//Amount of extra days to show in the overview
$GLOBALS["DAYS_PREBOOKING"]="6";
//Amount of bookings allowed every day
$GLOBALS["DAYLIMIT_BOOKING"]="4";
//Amount of bookings in total prebooking period
$GLOBALS["PRELIMIT_BOOKING"]="6";
//Array of available timeslots
$GLOBALS["SLOTS"]=array("06:30","07:45","09:00","10:15","11:30","12:45","14:00","15:15","16:30","17:45","19:00","20:15","21:30","22:45");
//Array of available machines
$GLOBALS["MACHINES"][]="Machine A";
$GLOBALS["MACHINES"][]="Machine B";
//Format for date for PHP function date()
$GLOBALS["DATEFORMAT"]="l (d M. Y)";
//Main URL for the project
$GLOBALS["BASE_URL"]=""; //Example: http://www.peliwash.nl
//Admin usernames and passwords
$GLOBALS["ADMIN"]["adminuser"]="adminpassword";
//Automatic bookings
//$GLOBALS["AUTOBOOKINGS"]["Wednesday"]["09:00"][2]="601";
?>
