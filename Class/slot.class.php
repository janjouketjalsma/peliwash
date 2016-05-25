<?php
class slot{
    var $m_sDate;
    var $m_sTime;
    private $m_iAvailable;
    private $m_sStatus;
    protected $m_oDB;
    private $m_aOutput;
    private $m_aOwners;
    private $m_aAvail_Machines;
    private $m_aMachine_Status;

    public function __construct($date,$time){
        $this->m_oDB        = new db();
        $this->m_sDate      = $date;
        $this->m_sTime      = $time;
        $this->m_iAvailable = count(controller::conf("MACHINES"));
        $this->m_sStatus    = "available";
        $this->m_aAvail_Machines = array_values(controller::conf("MACHINES"));
        foreach($this->m_aAvail_Machines as $machine){
            $this->m_aMachine_Status[$machine]="beschikbaar";
        }
        $this->m_aOutput    = "";
        $this->m_aOwners=array();
		if(!empty($date)){
			$this->update();
		}

    }

    public function update(){
        //Blocker voor tijden van 1 dag/////////
        $blockerDate="Wednesday (23 Nov. 2011)";
        $blockerTimes=array("10:15","11:30","12:45","14:00");
        $blockerText="Geen water!";
        ///////////////////////////////////
        if($this->m_sDate == $blockerDate && in_array($this->m_sTime,$blockerTimes)){
            $this->m_aOwners["A1"]=$blockerText;
            $this->m_aOwners["A2"]=$blockerText;
            $this->m_aOwners["B1"]=$blockerText;
            $this->m_aOwners["B2"]=$blockerText;
            $this->m_iAvailable=0;
            $this->m_aAvail_Machines=array();
            $this->m_aMachine_Status["A1"]='bezet';
            $this->m_aMachine_Status["A2"]='bezet';
            $this->m_aMachine_Status["B1"]='bezet';
            $this->m_aMachine_Status["B2"]='bezet';
        }else{
            $bookings=$this->m_oDB->entry_by_two_properties("booking","m_sDate",$this->m_sDate,"m_sTime",$this->m_sTime);
            if($bookings){
                foreach($bookings as $booking){
                    if(!empty($this->m_sDate)){
                        $this->m_aOwners[$booking["m_sMachine"]]=$booking["m_sOwner"];
                        $this->m_iAvailable-=1;
                        $this->m_aAvail_Machines=array_diff($this->m_aAvail_Machines, array(0=>$booking["m_sMachine"]));
                        $this->m_aMachine_Status[$booking["m_sMachine"]]='bezet';
                    }
                }
            }
            if($this->m_sDate==date(controller::conf("DATEFORMAT")) && $this->m_sTime < date("H:i")){
                $this->m_sStatus="unavailable";
            }
        }
        $this->update_status();
    }

    public function update_status(){
        if($this->m_iAvailable==0){
            $this->m_sStatus="unavailable";
        }
    }

    public function output(){
        $output["number_machines"]      = $this->m_iAvailable;
        $output["available_machines"]   = $this->m_aAvail_Machines;
        $output["owner"]                = $this->m_aOwners;
        $output["status"]               = $this->m_sStatus;
        $output["machine_status"]       = array_reverse($this->m_aMachine_Status);
        $output["date"]                 = urlencode($this->m_sDate);

        //Block machines
            $defecte_machines=unserialize(file_get_contents("Data/defect.php"));
            if(!empty($defecte_machines[0])){
                $i=0;
                $avail=array_flip($output["available_machines"]);
                foreach($defecte_machines as $machine){
                    $output["machine_status"][$machine]="bezet";
                    unset($avail[$machine]);
                    $output["owner"][$machine]="DEFECT";
                    $i++;
                }
                $output["available_machines"]=array_flip($avail);
                $output["number_machines"]-=$i;
            }
        //block machine
        return $output;
    }
}

class booking extends slot{
    var $m_sOwner;
    var $m_sMachine;
    var $m_sSecurity;
    var $m_sTimestamp;

    public function update(){
        parent::update();
    }
    public function __construct($date,$time){
        parent::__construct($date, $time);
        $this->m_sOwner     = "";
        $this->m_sMachine   = "";
        $this->m_sSecurity  = uniqid();
        $this->m_sTimestamp = date("U");
    }

    public function checkmachine($bookingID,$check){
        $bookinginfo=$this->m_oDB->entry_by_attribute("booking","ID",$bookingID);
		$bookinginfo=$bookinginfo[0];
        if($check==$bookinginfo["m_sSecurity"]){
            return $bookinginfo;
        }else{
			return FALSE;
		}
    }
}
?>
