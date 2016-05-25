<?php

class dbviews {
    var $request;
    var $return;
    var $curdate;
    
    public function __construct($database_object){
        $this->request  = "";
        $this->return   = array();
        $this->curdate  = date(controller::conf("DATEFORMAT"));
        $this->db       = new db();
    }

    public function  getdata ($request,$parameter="",$parameter2="",$parameter3="") {
        $this->request=$request;
        $this->return=array();
        //Determine action for request
        switch($request){
            default:
                echo "dbviews: requested data not found: ".$r."<BR>";
                die();
                break;
            case "all_slots":
                $this->return=array_merge($this->return,$this->all_slots($parameter,$parameter2,$parameter3));
                break;
            case "database_object":
                $this->return=array_merge($this->return,array("db"=>$this->db));
                break;
            case "all_users":
                $this->return=array_merge($this->return,array("all_users"=>$this->all_users($parameter)));
                break;
            case "washok":
                $this->return=$this->washokdata();
                break;
            case "bookingcounter":
                $this->return=$this->bookingcounter($parameter);
                break;
            case "bookingstats":
                $this->return=$this->bookingstats();
                break;
        }
        return $this->return;
    }
    private function bookingcounter($day=""){
        $current_bookings=count($this->db->listall("booking"));
        //$old_bookings=count($this->db->listall("oldbooking"));
        $return["current"]=$current_bookings;
        $return["old"]=$old_bookings;
        
        $today=date(controller::conf("DATEFORMAT"));
        $return["today"]=count($this->db->entry_by_property("booking","m_sDate",$today));

        $yesterday=date(controller::conf("DATEFORMAT"),strtotime("-1 day"));
        //$return["yesterday"]=count($this->db->entry_by_property("oldbooking","m_sDate",$yesterday));

        return $return;
    }
    private function all_users($selection=""){
        $i=0;
        if(!empty($selection)){
            switch($selection){
                case "active":
                    $data=$this->db->entry_by_property("user","inactive","FALSE");
                    break;
                case "inactive":
                    $data=$this->db->entry_by_property("user","inactive","TRUE");
                    break;
            }
            foreach($data as $user){
                $r[$user["ID"]]=ltrim($user["roomnumber"],0)." ".$user["firstname"];
            }
        }else{
            $data=$this->db->listall("user");
            foreach($data as $user){
                $r[$user["ID"]]=ltrim($user["roomnumber"],0)." ".$user["firstname"];
            }
        }
        
        uasort($r,"strnatcmp");
        return $r;
    }
    private function bookingstats(){
        $days=controller::conf("DAYS_PREBOOKING")+1;
        $times=count(controller::conf("SLOTS"));
        $machines=count(controller::conf("MACHINES"));
        $slotcount=$days*$times*$machines;
        $return["slotcount"]=$slotcount;
        $return["daymachines"]=$times*$machines;
        $otherdate="2 March 2011";
        $today="yesterday";
        $other=strtotime($otherdate);
        $other2=strtotime($today);
        $diff=$other2-$other;
        $olddays = floor($diff/86400);
        $return["slotcountold"]=$olddays*$machines*$times;
        return $return;
    }
    public function date2nl($data,$chop=FALSE){
        $dag[]="Maandag";
        $dag[]="Dinsdag";
        $dag[]="Woensdag";
        $dag[]="Donderdag";
        $dag[]="Vrijdag";
        $dag[]="Zaterdag";
        $dag[]="Zondag";
        $day[]="Monday";
        $day[]="Tuesday";
        $day[]="Wednesday";
        $day[]="Thursday";
        $day[]="Friday";
        $day[]="Saturday";
        $day[]="Sunday";
        if($chop){
            $remove=stristr($data, " ");
            $data=str_replace($remove, "", $data);
            //echo $data;
        }
        $datum = str_replace($day, $dag, $data);
        $datum = str_replace("Jan", "Jan", $datum);
        $datum = str_replace("Feb", "Feb", $datum);
        $datum = str_replace("Mar", "Maa", $datum);
        $datum = str_replace("Apr", "Apr", $datum);
        $datum = str_replace("May", "Mei", $datum);
        $datum = str_replace("Jun", "Jun", $datum);
        $datum = str_replace("Jul", "Jul", $datum);
        $datum = str_replace("Aug", "Aug", $datum);
        $datum = str_replace("Sep", "Sep", $datum);
        $datum = str_replace("Oct", "Okt", $datum);
        $datum = str_replace("Nov", "Nov", $datum);
        $datum = str_replace("Dec", "Dec", $datum);
        return $datum;
    }
    private function washokdata(){
        $date=$this->curdate;
        $curtime=date("H:i");
        //$curtime="15:00";
        $future_times=3;
        $i=1;
        $all_times=controller::conf("SLOTS");
        //find times
        foreach($all_times as $time){
            if($time <= $curtime){
                //previous time
                $times[0]=$times[1];
                //Current time
                $times[1]=$time;
            }elseif($time > $curtime && $i < $future_times+2){
                $i++;
                //future times
                $times[$i]=$time;
            }
        }
        if(!isset($times[0])){
            $times[0]="-";
        }
        $i=0;
        sort($times,SORT_NUMERIC);
        //get data
        foreach ($times as $time){
            $slot=new slot($date,$time);
            $slotinfo=$slot->output();
            $return[$i]["date"]=$this->date2nl($date);
            $return[$i]["time"]=$time;
            $user=new user();
            $owners=array();
            foreach($slotinfo["owner"] as $machine => $userID){
                if(!empty($userID)){
                    $user->userinfo($userID);
                    $userinfo["firstname"]=$user->firstname;
                    $userinfo["roomnumber"]=ltrim($user->roomnumber,0);
                    $userinfo["status"]="Bezet";
                    $userinfo["facebook"]=$user->facebook;
                    $owners[$machine]=$userinfo;
                }
            }
            foreach($owners as $machineID => $userinfo){
				$machineID=str_replace(" ","",$machineID);
				$return[$i]["owner"][$machineID]=$userinfo["firstname"];
				$return[$i]["roomnumber"][$machineID]=$userinfo["roomnumber"];
				$return[$i]["status"][$machineID]=$userinfo["status"];
				$return[$i]["facebook"][$machineID]=$userinfo["facebook"];
            }
            unset($slot);
            $i++;
        }
        return $return;
    }
    private function all_slots($label=FALSE,$extend=FALSE,$ajax=FALSE){
            if(date("U") < strtotime("21 February 2012")){
				$date=date(controller::conf("DATEFORMAT"),strtotime("21 February 2012"));
				$today=strtotime("21 February 2012");
			}else{
				$date=$this->curdate;
				$today=date("U");
			}
        
        $days_prebooking=controller::conf("DAYS_PREBOOKING");
        if($extend){
            $days_prebooking=40;
        }
        //Loop days set in config
        while($day <= $days_prebooking){
            //Loop timeslots set in config
            foreach(controller::conf("SLOTS") as $time){
                $i++;
                //Check if date is in blocked dates
                $geblokkeerd[]="Monday (15 Aug. 2011)";

                if(!in_array($date,$geblokkeerd)){
                    if(!$ajax){
                        //Create slot object for each slot
                        $slot=new slot($date,$time);
                        //Add slot data to return array
                        $slotinfo=$slot->output();
                        $status=$slotinfo["status"];
                        if($label && $status=="available"){
                            if($date==date(controller::conf("DATEFORMAT"))){
                                $return["Vandaag"][$date."|".$time]=$this->date2nl($date,TRUE)." ".$time;
                            }elseif($date==date(controller::conf("DATEFORMAT"),strtotime("+1 days"))){
                                $return["Morgen"][$date."|".$time]=$this->date2nl($date,TRUE)." ".$time;
                            }elseif($date==date(controller::conf("DATEFORMAT"),strtotime("+2 days"))){
                                $return["Overmorgen"][$date."|".$time]=$this->date2nl($date,TRUE)." ".$time;
                            }else{
                                $return[$this->date2nl($date)][$date."|".$time]=$this->date2nl($date,TRUE)." ".$time;
                            }
                        }elseif(!$label){
                            $return[$this->date2nl($date)][$time]=$slot->output();
                            $return[$this->date2nl($date)][$time]["id"]=$i;
                        }
                        //Unset the slot object
                        unset($slot);
                    }else{
                        $i++;
                        $return[$this->date2nl($date)][$time][0]=$date;
                        $return[$this->date2nl($date)][$time][1]=$i;
                    }
                }
            }
            //Increase day counter
            $day++;
            //Update date
            $date=date(controller::conf("DATEFORMAT"),strtotime("+".$day." days",$today));
        }
        if(!empty($return)){
            return $return;
        }else{
            //echo "dbviews::all_slots: no data from query";
        }
    }
}
?>
