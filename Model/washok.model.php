<?php
class model_washok{
    var $Data;
	var $Template;
    public function  __construct($postParams,$getParams) {
		//error_reporting(E_ALL);
        $this->m_oDB        = new db();
        $this->Data    = array();
		$this->Template= "";
        $this->getData();
		$this->NoCache=TRUE;
    }
	
	public function getData(){
		$data_helper=new dbviews($this->m_oDB);
		$this->Data["data"]=$data_helper->getdata('washok');
		$this->Data["time"]=date("H:i");
		if(date("U")<date("U", mktime(0, 0, 0, 2, 21, 2012))){
			//$this->Data["offline"]='<div id="offline"><br><BR><BR><BR><br><br><br><br>Vanaf 21 februari wordt deze monitor in gebruik genomen.</div>';
		}
		$this->Template="washok.tpl";
	}

}
?>