<?php
class model_cancel{
	var $Data;
	var $Template;
        var $getParams;
        var $postParams;
        var $NoCache;
        var $ClearCache;
    public function  __construct($postParams,$getParams) {
        $this->m_oDB   = new db();
        $this->Data    = "";
        $this->getParams=$getParams;
        $this->postParams=$postParams;
        $this->NoCache=TRUE;
        $this->ClearCache=FALSE;
		$this->Template="";
        $this->getData();
	}
	public function getData(){
		//print_r($this->postParams);
		//Check for requests from AJAX
		if(!empty($this->postParams["id"])){
			$cancel=booking::checkmachine($this->postParams["id"],$this->postParams["check"]);
			$this->m_oDB->delete_entry("booking",$this->postParams["id"]);
			$this->ClearCache=TRUE;
			$this->Data="Annulering voor ".$cancel["m_sMachine"]." gelukt.";
		}elseif(!empty($this->getParams["form"])){
			//error_reporting(E_ALL);
			$booking=new booking("","");
			$cancel=$booking->checkmachine($this->getParams["id"],$this->getParams["check"]);
			if(is_array($cancel)){
				$this->Template="home/cancel.tpl";
				$this->Data["machine"]=$cancel["m_sMachine"];
				$this->Data["date"]=dbviews::date2nl($cancel["m_sDate"]);
				$this->Data["time"]=$cancel["m_sTime"];
			}else{
				echo "Reservering bestaat niet (meer)";
			}
		}else{
			//Include default homepage
			$this->Template="home.tpl";
			include("Model/home.model.php");
			model_home::getData();
		}
	}
}
?>