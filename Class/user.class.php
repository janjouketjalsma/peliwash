<?php
class user {
    var $code;
    var $firstname;
    var $roomnumber;
    var $email;
    var $ID;
    private $db;
    var $inactive;
    var $facebook;
    var $custom_password;

    public function __construct($code=""){
        $this->code             =$code;
        $this->firstname        ="-";
        $this->roomnumber       ="-";
        $this->email            = "";
        $this->custom_password  = "FALSE";
        $this->facebook         = "";
        $this->db               = new db();
        $this->ID               = "";
        $this->inactive         = "TRUE";
        if(!empty($this->code)){
            $this->update();
        }
    }
    public function gencode(){
        $trialcode="act".substr(md5(uniqid()),0,5);
        if(!is_array($this->db->entry_by_property("user","code",$trialcode))){
            $this->code=$trialcode;
        }else{
            $this->gencode();
            echo "Dubbele code, probeert opnieuw<br>";
        }
    }
    public function update($code=""){
        if(!empty($code)){
            $this->code=$code;
        }else{
            //echo "geen code";
	}
        $userinfo=$this->db->entry_by_property("user","code",$this->code);
        $this->firstname=$userinfo[0]["firstname"];
        $this->roomnumber=$userinfo[0]["roomnumber"];
        $this->custom_password=$userinfo[0]["custom_password"];
        $this->email=$userinfo[0]["email"];
        $this->facebook=$userinfo[0]["facebook"];;
        if(!empty($userinfo[0]["inactive"])){
            $this->inactive=$userinfo[0]["firstname"];
        }
	$this->ID=$userinfo[0]["ID"];
    }
    public function ID(){
        return $this->ID;
    }
    public function userinfo($ID){
        //In case of autobookings no UserID is used, so manual var fill is needed down here
        if(!is_numeric($ID)){
            $this->firstname=$ID;
        }else{
            $userinfo=$this->db->entry_by_attribute("user","ID",$ID);
            $this->code=$userinfo[0]["code"];
            $this->update();
        }
    }

    public function allowed($rdate,$time,$request){
        //Check if booking is allowed for this user according to the quota
        if(empty($this->ID)){
            return FALSE;
        }
        if($this->inactive=="TRUE"){
            return FALSE;
        }
        //Get period array
        $day=0;
        $date=date(controller::conf("DATEFORMAT"));
        while($day <= controller::conf("DAYS_PREBOOKING")){
            $date=date(controller::conf("DATEFORMAT"),strtotime("+".$day." days"));
            $period[]=$date;
            $day++;
        }
        //Get all bookings
        $current_bookings   =$this->db->entry_by_property("booking","m_sOwner",$this->ID);
        //Get quota
        $period_quota       =controller::conf("PRELIMIT_BOOKING");
        $day_quota          =controller::conf("DAYLIMIT_BOOKING");
        //Loop bookings
        if(is_array($current_bookings)){
            foreach($current_bookings as $booking){
                if(in_array($booking["m_sDate"],$period)){
                    $period_quota-=1;
                    if($rdate==$booking["m_sDate"]){
                        $day_quota-=1;
                    }
                }
            }
        }
        if($period_quota<count($request)){
            return FALSE;
        }elseif($day_quota<count($request)){
            return FALSE;
        }else{
            return TRUE;
        }
        
    }

    public function bookinginfo($userID){
        //Get period array
        $date=date(controller::conf("DATEFORMAT"));
        while($day <= controller::conf("DAYS_PREBOOKING")){
            $date=date(controller::conf("DATEFORMAT"),strtotime("+".$day." days"));
            $period[]=$date;
            $day++;
        }
        //Check user ID
        if(!empty($userID)){
            //Get bookings by userID (CAN BE REPLACED BY XPATH IN DB CLASS!)
            $user_bookings   =$this->db->entry_by_property("booking","m_sOwner",$this->ID);
            //Get quota
            $period_quota       =controller::conf("PRELIMIT_BOOKING");
            $day_quota          =controller::conf("DAYLIMIT_BOOKING");
            //Put quota in return-array
            $return["period_quota"]=$period_quota;
            $return["day_quota"]=$day_quota;
            //Loop bookings
            if(is_array($user_bookings)){
                foreach($user_bookings as $booking){
                    $return["booking"][$booking["m_sDate"]]=$booking["m_sMachine"];
                    if(in_array($booking["m_sDate"],$period)){
                        $return["current_booking"][$booking["m_sDate"]]=$booking["m_sMachine"];
                    }
                }
                return $return;
            }

        }else{
            return FALSE;
        }
    }
}
?>
