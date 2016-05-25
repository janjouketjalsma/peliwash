<?php
class tekstiploader{
    public function __construct(){
        
    }
    public static function load($class){
        switch($class){
            case "slot":
                include("Class/slot.class.php");
                break;
            case "db":
                include("Class/sqldb.class.php");
                break;
            case "dbviews":
                include("Class/dbviews.class.php");
                break;
            case "user":
                include("Class/user.class.php");
                break;
            case "booking":
                include("Class/slot.class.php");
                break;
			case "post":
				include("Class/post.class.php");
				break;
        }
    }
}
?>
