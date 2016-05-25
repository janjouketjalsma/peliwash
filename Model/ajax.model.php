<?php
class model_ajax{
    var $Data;
	var	$smarty;
	var $params;
	var $m_oDB;
	var $Template;
	var $NoCache;
    public function  __construct($postParams,$getParams) {
        $this->m_oDB        = new db();
        $this->Data    		= "";
		$this->Template		= "";
		$this->params		= $_GET;
		$this->NoCache		= TRUE;
		$this->getData($this->params);
		$this->ClearCache	= FALSE;
    }

	public function getData($request){
		$data_helper=new dbviews($this->m_oDB);
		switch($request["r"]){
            case "slot_tooltip":
				//error_reporting(E_ALL);
				$user=new user();
                $slot=new slot($request["date"],$request["time"]);
                $this->Data["day"]		= $data_helper->date2nl($slot->m_sDate);
                $this->Data["time"]		= $request["time"];
                $slotinfo=$slot->output();
                $newinfo=$slotinfo;
                foreach($slotinfo["owner"] as $machine => $userid){
                    if(!is_numeric($userid)){
                        $newinfo["owner"][$machine]=$userid;
                    }else{
                        $user->userinfo($userid);
                        $newinfo["owner"][$machine]=trim(ltrim($user->roomnumber,0));
                    }
                }
                $this->Data["slotinfo"]=$newinfo;
                $this->Template="home/slot_tooltip.tpl";
                break;
            case "days":
                $this->Data["slots"]		=$data_helper->getdata("all_slots");
                $this->Template="home/days.tpl";
                break;
            case "washokfooter":
				$this->Data["data"]		=$data_helper->getdata('washok');
				$this->Template="washok/footer.tpl";
				file_put_contents("lastseen.txt",Date("U"));
				break;
            case "washokcontent":
                include("Class/washokContentLoader.class.php");
                new washokContentLoader();
                break;
            case "mtime":
                echo filemtime("mtime.txt");
                break;
			case "quickmenu":
				$this->Data["quickmenu"]=$this->quickmenu();
				$this->Template="home/quickmenu.tpl";
				break;
        }
	}
	
	private function quickmenu(){
		$today				= date("U");
        $date				= date(controller::conf("DATEFORMAT"));
        $days_prebooking	= controller::conf("DAYS_PREBOOKING");
        $day				= 0;
        //Loop days set in config
        while($day <= $days_prebooking){
            //Loop timeslots set in config
            foreach(controller::conf("SLOTS") as $time){
				$slot		= new slot($date,$time);//Create slot object for each slot
				$slotinfo	= $slot->output();//Add slot data to return array
				$status		= $slotinfo["status"];//Set slot status
				$iAvail		= $slotinfo["number_machines"];//Set number of available machines
				if($status=="available"){
					if($date==date(controller::conf("DATEFORMAT"))){
						$return["Vandaag"][$date."|".$time]=dbviews::date2nl($date,TRUE)." ".$time." (".$iAvail." vrij)";
					}elseif($date==date(controller::conf("DATEFORMAT"),strtotime("+1 days"))){
						$return["Morgen"][$date."|".$time]=dbviews::date2nl($date,TRUE)." ".$time." (".$iAvail." vrij)";
					}elseif($date==date(controller::conf("DATEFORMAT"),strtotime("+2 days"))){
						$return["Overmorgen"][$date."|".$time]=dbviews::date2nl($date,TRUE)." ".$time." (".$iAvail." vrij)";
					}else{
						$return[dbviews::date2nl($date)][$date."|".$time]=dbviews::date2nl($date,TRUE)." ".$time." (".$iAvail." vrij)";
					}
					//Unset object to avoid data clogging
					unset($slot);
				}
            }
            //Increase day counter
            $day++;
            //Update date
            $date=date(controller::conf("DATEFORMAT"),strtotime("+".$day." days",$today));
        }
        return $return;
    }
	
}
?>