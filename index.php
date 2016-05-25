
<?php
    error_reporting(0);
    include("controller.php");
	if($_GET["mode"]=="ajax"){
		//$controller=new controller(TRUE,$_GET);
	}else{
		$controller=new controller();
	}
    
    //echo "Offline wegens onderhoud";
?>

