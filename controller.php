<?php
/**
 * The controller loads config and decides what to do
 *
 * @author jj
 */
class controller {
    public function __construct($AJAX=FALSE,$AJAXrequest=""){
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		date_default_timezone_set("Europe/Amsterdam");
        include("config.php");									//Load config
        include("Class/tekstiploader.class.php");				//Include autoloading class
        spl_autoload_register(array('tekstiploader', 'load'));	//Register loader
        include("Functions/mail.php");							//Load mail function
		if($AJAX){
			$requestedPage  = "ajax";							//Set page var to AJAX
		}else{
			$requestedPage  = $_GET["page"];					//Set page var to whatever is requested
		}
        $postParams         = $_POST;
        $getParams          = $_GET;
		/*
		* Below is a patch for the old admin screen.
		* Update ASAP
		*
		*/
		if($_GET["page"]=="admin"){
			include("smarty/Smarty.class.php");
			//Create display object
			$this->smarty       = new Smarty;
			$this->smarty->caching=0;
			//Create user object
			//Set page
			$this->page         = "admin";
			$this->slot			= new slot("","");
			$this->db			= new db();
			//Get HTML
			$this->get_smarty_data($_GET["page"]);
		}else{
		    $this->pageFactory($requestedPage,$postParams,$getParams);//Build page object
		}
    }

    public function pageFactory($requestedPage,$postParams,$getParams){
        if(!empty($requestedPage)){								//If there was an explicit page request
            include("Model/".$requestedPage.".model.php");		//Try to include model
            if(class_exists("model_".$requestedPage)){			
                include('smarty/Smarty.class.php');
                $classname="model_".$requestedPage;				//Build model name
                $model=new $classname($postParams,$getParams);	//Create model instance
				if(!empty($model->Template)){	
					/* 
					Now using smarty to generate HTML
					*/
								//include smarty class
					$view=new Smarty();							//make view object
					if($model->ClearCache){
						$view->clearAllCache(); 				//Optionally clear cache
					}elseif($model->NoCache){
						$view->caching=0;
					}
					$view->assign($model->Data);				//assign data to view
					$view->display($model->Template);			//Generate HTML
				}else{
					echo $model->Data;							//echo raw Data
				}
            }else{												//Model is not found
                echo "model not found, please return to previous page";
            }
        }else{													//No page requested
            $this->pageFactory("home",$postParams,$getParams);	//display homepage
        }
    }
    
    //Display function
    public function get_smarty_data($request){
        $data_helper=new dbviews($this->db);
        switch($request){
            case "admin":
                error_reporting(1);
                $admin=$this->conf("ADMIN");
                $authenticated=FALSE;
                foreach($admin as $username => $password){
                    if($_REQUEST["user"]==$username && $_REQUEST["password"]==$password){
                        $authenticated=TRUE;
                    }
                }
                if($authenticated){
                    $task=$_POST["task"];
                    //Find tasks
                    if(!is_array($task)){
                        $task[]=$task;
                    }
                    if(!empty($task[0])){
                        $this->smarty->assign("backbutton",TRUE);
                    }
                    //Perform tasks actions
					$user=new user();				
                    foreach($task as $action){
                        switch($action){
                            case "userinfo":
                                $user->userinfo($_POST["userID"]);
                                $userinfo["userID"]=$_POST["userID"];
                                $userinfo["firstname"]=$user->firstname;
                                $userinfo["roomnumber"]=$user->roomnumber;
                                $userinfo["email"]=$user->email;
                                $userinfo["inactive"]=$user->inactive;
                                $userinfo["code"]=$user->code;
                                $this->smarty->assign("userinfo",$userinfo);

                                break;
                            case "reset_user":
                                $user->userinfo($_POST["userID"]);
                                $this->db->delete_entry("user",$_POST["userID"]);
                                $emptyuser=new user;
                                $emptyuser->roomnumber=$user->roomnumber;
                                $emptyuser->gencode();
                                $this->db->new_entry($emptyuser,$_POST["userID"]);
                                echo "Gebruikersdetails zijn gereset en er is een nieuwe activeringscode aangemaakt<hr>";
                                break;
                            case "delete_appointments":
                                $appointments=$this->db->entry_by_property("booking", "m_sOwner", $_POST["userID"]);
                                if(is_array($appointments)){
                                    foreach($appointments as $appointment){
                                        $this->db->delete_entry("booking",$appointment["ID"]);
                                    }
                                }
                                $this->smarty->clearAllCache();
                                echo "Wasafspraken gewist<hr>";
                                break;
                            case "manual":
								if($user->inactive=="TRUE"){
                                $user->userinfo($_POST["userID"]);
                                $this->smarty->assign("date",dbviews::date2nl(date("l j M. Y")));
                                $this->smarty->assign("authenticated",TRUE);
                                $userinfo["code"]=$user->code;
                                $userinfo["roomnumber"]=$user->roomnumber;
                                $this->smarty->assign("userinfo",$userinfo);
                                $this->smarty->assign("prebooking",$this->conf("DAYS_PREBOOKING"));
                                $this->smarty->assign("daylimit",$this->conf("DAYLIMIT_BOOKING"));
                                $this->smarty->assign("prelimit",$this->conf("PRELIMIT_BOOKING"));
                                $this->smarty->assign("period",$this->conf("DAYS_PREBOOKING"));
								$this->smarty->display("admin/manual.tpl");
								die();
								}else{
								echo "Gebruiker is geactiveerd, gebruik reset om een nieuwe code aan te maken.";
								}
                                
                            case "booking_formdata":
                                $all_users=$data_helper->getdata('all_users');
                                $all_users=array(0=>" ")+$all_users["all_users"];
                                $this->smarty->assign("all_users",$all_users);
                                $this->smarty->assign("booking",$_POST["booking"]);
                                $info=explode("|",$_POST["booking"]);
                                $date=$info[0];
                                $time=$info[1];
                                $this->slot=new slot($date,$time);
                                $slotinfo=$this->slot->output();
                                $this->smarty->assign("dateNL",dbviews::date2nl($date));
                                $this->smarty->assign("booking_date",$date);
                                $this->smarty->assign("time",$time);
                                $this->smarty->assign("selected_machines",array_slice($slotinfo["available_machines"],0,1));
                                $this->smarty->assign("machines",$slotinfo["available_machines"]);
                                $this->smarty->assign("booking_formdata",TRUE);
                                break;
                            case "admin_email":
                                mail("error@tekst-ip.nl","PELIWASH peliwash foutmelding","Het probleem: ".$_POST["problem"]." De omschrijving: ".$_POST["comment"]);
                                echo "De melding is verstuurd. Er wordt zo spoedig mogelijk een oplossing gezocht";
                                break;
                            case "make_booking":
                                $info=explode("|",$_POST["booking"]);
                                $date=$info[0];
                                $time=$info[1];
                                foreach($_POST["machines"] as $machine){
                                    $booking=new booking($date,$time);
                                    $booking->m_sOwner=$_POST["userID"];
                                    $booking->m_sMachine=$machine;
                                    $this->db->new_entry($booking);
                                }
                                $this->smarty->clearAllCache();
                                echo "Wasafspraak gemaakt<hr>";
                                break;
                            case "defect":
                                file_put_contents("Data/defect.php",serialize($_POST["defect"]));
                                break;
							case "add_post":
								echo "Bericht: ".$_POST["posttitle"]." toegevoegd";
								$post=new post();
								$post->title=$_POST["posttitle"];
								$post->category=$_POST["postcategory"];
								$post->contents=nl2br($_POST["postcontent"]);
								$this->db->new_entry($post);
								break;
							case "delete_post":
								echo "Bericht verwijderd";
								$this->db->delete_entry("post",$_POST["postID"]);
								break;
                        }
                    }
					$this->smarty->assign("posts",$this->db->listall("post"));
                    $this->smarty->assign('book',$data_helper->getdata('all_slots',TRUE,TRUE));
                    $all_users=$data_helper->getdata('all_users');
                    $this->smarty->assign("users",$all_users);
                    $activeusers=$data_helper->getdata('all_users',"active");
                    $inactiveusers=$data_helper->getdata('all_users',"inactive");
                    $bookingcounts=$data_helper->getdata("bookingcounter");
                    $bookingstats=$data_helper->getdata("bookingstats");
                    //stats
                    $this->smarty->assign("usercount",count($all_users["all_users"]));
                    $this->smarty->assign("activeusercount",count($activeusers["all_users"]));
                    $this->smarty->assign("activeuserpercentage",$this->percent(count($activeusers["all_users"]),count($all_users["all_users"])));
                    $this->smarty->assign("bookingcount",$bookingcounts["current"]);
                    $this->smarty->assign("bookingcount_old",$bookingcounts["old"]);
                    $this->smarty->assign("bookingcount_total",$bookingcounts["current"]+$bookingcounts["old"]);
                    $this->smarty->assign("capacitypercentagetotal",$this->percent($bookingcounts["old"],$bookingstats["slotcountold"]));
                    $this->smarty->assign("capacitypercentagetoday",$this->percent($bookingcounts["today"],$bookingstats["daymachines"]));
                    $this->smarty->assign("capacitypercentageyesterday",$this->percent($bookingcounts["yesterday"],$bookingstats["daymachines"]));
                    //
                    $this->smarty->assign("authenticated",TRUE);
                    $this->smarty->assign("user",$_REQUEST["user"]);
                    $this->smarty->assign("password",$_REQUEST["password"]);
                    $this->smarty->assign("defect",unserialize(file_get_contents("Data/defect.php")));
                    //offline button
                    $lastseen=file_get_contents("lastseen.txt");
                    $tensecondsago=Date("U",  strtotime("-10 seconds"));
                    if($lastseen>=$tensecondsago){
                        $this->smarty->assign("washokstatus",TRUE);
                        
                    }else{
                        $this->smarty->assign("washokstatus",FALSE);
                    }
                    //offline button
                    $this->smarty->display("admin/portal.tpl");
                }else{
                    $this->smarty->display("admin/login.tpl");
                    die();
                }
                break;
            case "washok":
                $this->db->HourlyBackup();
                $this->archive_old();
                $this->autobook();
                $this->smarty->assign("data",$data_helper->getdata('washok'));
                $this->smarty->assign("time",date("H:i"));
                //
                $this->smarty->assign("pelinieuws",$this->pelinieuws());
                if(date("U")<date("U", mktime(0, 0, 0, 3, 2, 2011))){
                    $this->smarty->assign("offline",'<div id="offline"><br><BR><BR><BR><br><br><br><br>Vanaf 2 maart wordt deze monitor in gebruik genomen.</div>');
                }
                $this->smarty->display("washokHD.tpl");
                break;
            case "FAQ":
                $this->smarty->assign("daglimiet",controller::conf("DAYLIMIT_BOOKING"));
                $this->smarty->assign("periodelimiet",controller::conf("PRELIMIT_BOOKING"));
                $this->smarty->assign("periode",controller::conf("DAYS_PREBOOKING")+1);
                $FAQ=$this->smarty->fetch("faq.tpl");
                //Display activation screen
                $this->smarty->assign("lightboxcontent",$FAQ);
                $this->smarty->assign("lightboxtitle","F.A.Q.");
                $this->get_smarty_data("home");
                break;
            //case "testing":
            case "home";
                //echo "Peliwash is tijdelijk niet te bereiken.";
                //die();
                $this->smarty->assign('peliforum',$this->peliforum());
                $this->smarty->assign('pelinieuws',$this->pelinieuws());
                $this->smarty->assign('slots',$data_helper->getdata('all_slots'));
                $this->smarty->assign('quickmenu',$data_helper->getdata('all_slots',TRUE));
                $lightboxtitle=$this->smarty->get_template_vars("lightboxtitle");
                if(empty($lightboxtitle)){
                        $this->smarty->assign("lightboxtitle","Melding");
                }
                $this->smarty->display("AJAXhomefull.tpl");
                break;
            default:
                $this->smarty->assign("error","De gevraagde pagina: '".$request."' bestaat niet");
                $this->get_smarty_data("home");
        }
    }

    //Other functions
    public function pageswitcher(){
        if(!empty($_GET['page'])){
            return $_GET['page'];
        }else{
            return $this->conf('DEFAULT_PAGE');
        }
    }

    public function conf($property){
        if(isset($GLOBALS[$property])){
            return $GLOBALS[$property];
        }else{
            echo "FATAL ERROR: Config ".$property." not found";
            die();
        }
    }

    public function activationscreen(){
        $return=$this->smarty->fetch("activate.tpl");
        return $return;
    }
    
    public function peliforum(){
        $maxmessages=4;
        $xml=simplexml_load_file("http://www.pelikaanhof.nl/peliforum/syndication.php");
        $i=1;
        foreach($xml->channel->item as $post){
            if($i<=$maxmessages){
                $return.='<div style="margin-left:8px; margin-bottom:4px;"><a target="_blank" class="bold hover" href="'.(string) $post->link[0].'">'.substr((string) $post->title[0],0,30).'</a>';
                $return.="<br>";
                $return.='<a class="hover" target="_blank" href="'.(string) $post->link[0].'">'.substr(strip_tags((string) $post->description[0]),0,70).'....</a></div>';
                $i++;
            }
        }
        return $return;
    }

    public function pelinieuws(){
        $xml=simplexml_load_file("http://www.pelikaanhof.nl/nieuws.xml");
        $i=0;
        $max=3;
        if(!empty($xml->post->content[0])){
            foreach($xml->post as $post){
                    $i++;
                    $r[$i]["anchor"]    =(string) $post->anchor[0];
                    $r[$i]["title"]     =(string) $post->title[0];
                    $r[$i]["content"]   =stripslashes(html_entity_decode((string) $post->content[0]));
            }
        }
        $r=array_reverse($r,TRUE);
        $i=0;
        foreach($r as $post){
                if($i<=$max){
                    $i++;
                $return.= '<div class="pelinieuwsitem" style="margin-bottom:4px;margin-left:8px;"><a class="bold hover" target="_blank" href="http://www.pelikaanhof.nl/">'.$post["title"].'</a><br>';
                $return.= '<a class="hover" target="_blank" href="http://www.pelikaanhof.nl/">'.substr(strip_tags($post["content"]),0,80).'....</a></div>';
                }
        }
        return (string)$return;
    }
    public function autobook(){
        $autobookings=$this->conf("AUTOBOOKINGS");
        $today=date("U");
        //Get period array
        $day=0;
        while($day <= $this->conf("DAYS_PREBOOKING")){
            $pwdate=date(controller::conf("DATEFORMAT"),strtotime("+".$day." days",$today));
            if(in_array(date("l",strtotime("+".$day." days",$today)),array_keys($autobookings))){
                $this->slot->m_sDate=$pwdate;
                foreach($autobookings[date("l",strtotime("+".$day." days",$today))] as $abTime => $abInfo){
                    $abMade=0;
                    $this->slot->m_sTime=$abTime;
                    $this->slot->update();
                    $slotinfo=$this->slot->output();
                    foreach($slotinfo["owner"] as $owner){
                        if(in_array($owner,$abInfo)){
                            $abMade++;
                        }
                    }
                    $abRequest=array_keys($abInfo);
                    if($abMade==$abRequest[0]){
                    }else{
                        $i=0;
                        $abMake=$abRequest[0]-$abMade;
                        while($i < $abMake){
                            $machines=array_values($slotinfo["available_machines"]);
                            $owner=array_values($abInfo);
                            $booking=new booking($this->db,$pwdate,$abTime);
                            $booking->m_sOwner=$owner[0];
                            $booking->m_sMachine=$machines[$i];
                            $this->slot->update();
                            $slotinfo=$this->slot->output();
                            echo $slotinfo["number_machines"];
                            if($slotinfo["number_machines"] >= 0){
                                $this->db->new_entry($booking);
                            }
                            $i++;
                        }
                    }
                }
                break;
            }
            $day++;
        }
    }

    public function archive_old(){
        //Get period array
        $day=0;
        $date=date(controller::conf("DATEFORMAT"));
        $datetest = DateTime::createFromFormat(controller::conf("DATEFORMAT"),$date);
        $datetest->format(controller::conf("DATEFORMAT"));
        while($day <= controller::conf("DAYS_PREBOOKING")){
            $date=date(controller::conf("DATEFORMAT"),strtotime("+".$day." days"));
            $period[]=$date;
            $day++;
        }
        //Archive and delete old and broken
        foreach($this->db->listall("booking") as $booking){
            $bookingdate=DateTime::createFromFormat(controller::conf("DATEFORMAT"),(string)$booking->m_sDate);
            if(!is_object($bookingdate)){
                $this->db->delete_entry("booking",(string)$booking->attributes());
            }else{
                $bookingdate_u=$bookingdate->format("U");
                if($bookingdate_u<date("U",strtotime("-1 week",date("U"))) && $bookingdate_u>100){
                    //echo "Boeking gearchiveerd<br>";
                    $ID=(string)$booking->attributes();
                    $archived_booking=new booking("",(string)$booking->m_sDate,(string)$booking->m_sTime);
                    $archived_booking->m_sOwner=(string)$booking->m_sOwner;
                    $archived_booking->m_sMachine=(string)$booking->m_sMachine;
                   $this->db->new_entry($archived_booking,"","oldbooking");
                   $this->db->delete_entry("booking",(string)$ID);
                    //$this->user->userinfo($booking->m_sOwner);
                    //echo $this->user->firstname;
                    //$datetime=DateTime::createFromFormat($this->conf("DATEFORMAT")."H:i",$booking->m_sDate.$booking->m_sTime);
                    //if($datetime){
                    //    $archive[date_format($datetime,"U")][(string)$booking->m_sMachine]["firstname"]=$this->user->firstname;
                    //    $archive[date_format($datetime,"U")][(string)$booking->m_sMachine]["roomnumber"]=$this->user->roomnumber;
                    //}
                }else{
                    //Boeking valt wel binnen periode... eventuele actie aan verbinden...
                }
            }
        }
            //ob_start();
            //print_r( $archive );
            //$output = ob_get_clean();
            //file_put_contents("Data/archive_".date("dmY").".txt",$output);
    }

    public function AJAXinterface($request){
        $data_helper=new dbviews($this->db);
        switch($request["r"]){
            case "slot_tooltip":
                error_reporting(1);
                $this->smarty->caching=0;
                $slot=new slot($this->db,$request["date"],$request["time"]);
                $this->smarty->assign("day",$data_helper->date2nl($slot->m_sDate));
                $this->smarty->assign("time",$request["time"]);
                $slotinfo=$slot->output();
                $newinfo=$slotinfo;
                foreach($slotinfo["owner"] as $machine => $userid){
                    if(!is_numeric($userid)){
                        $newinfo["owner"][$machine]=$userid;
                    }else{
                        $this->user->userinfo($userid);
                        $newinfo["owner"][$machine]=trim(ltrim($this->user->roomnumber,0));
                    }
                }
                $this->smarty->assign("slotinfo",$newinfo);
                $this->smarty->display("AJAXhome/slot_tooltip.tpl");
                break;
             case "days":
                 error_reporting(1);
                 $slots=$data_helper->getdata("all_slots");
                 //print_r($slots);
                 $this->smarty->assign("slots",$slots);
                 $this->smarty->display("AJAXhome/days.tpl");
                 break;
             case "washokfooter":
                  $this->smarty->assign("data",$data_helper->getdata('washok'));
                  $this->smarty->display("washokHD/footer.tpl");
                  file_put_contents("lastseen.txt",Date("U"));
                  break;
              case "washokcontent":
                  include("Class/washokContentLoader.class.php");
                  new washokContentLoader();
                  break;
              case "mtime":
                  echo filemtime("Data/booking.xml");
                  break;
        }
    }

    public function percent($num_amount, $num_total) {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 0);
    return $count;
    }
}
?>
