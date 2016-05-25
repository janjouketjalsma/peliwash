<?php
class model_activation{
    var $Data;
	var $getParams;
	var $m_oDB;
	var $Template;
	var $ClearCache;
	var $postParams;
	
    public function  __construct($postParams,$getParams) {
		//error_reporting(E_ALL);
        $this->m_oDB        = new db();
        $this->Data    		= array();
		$this->Template		= "";
		$this->getParams	= $getParams;
		$this->postParams	= $postParams;
		$this->ClearCache	= TRUE;
		$this->NoCache		= TRUE;
		$this->getData();
	}
	
	public function getData(){
		if(empty($this->postParams["code"]) && empty($this->postParams["usercode"])){
			$this->Template	= "activate.tpl";
		}elseif(empty($this->postParams["userID"])){
			$this->step2();
		}else{
			$this->step3();
		}
	}
	
	public function Step2(){
		include_once("Functions/mail.php");//Include mailing class for emailcheck
		$user		= new user($this->postParams["code"]);//Create user for checks
		$userid		= $user->ID();//Get userID for current code
		$error		= "";//Set temp var error to empty
		$email		= new peliwashmail("internet",$this->postParams["email"]);//Create email class
		
		if(empty($this->postParams["code"])){
			$error.="Activeringscode was leeg.<br>";
		}
		if($user->inactive=="FALSE" || $user->custom_password=="TRUE"){
			$error.="Je account is al geactiveerd.<br>";
		}
		if(empty($userid)){
			$error.="De ingevoerde activeringscode bestaat niet, of je account is al geactiveerd.<br>";
		}
		if(empty($this->postParams["name"])){
			$error.="Het veld 'naam' is verplicht.<br>";
		}
		if(!$email->checkreceipent()){
			$error.="Het e-mailadres klopt niet.<br>";
		}
		if(!empty($error)){
			$this->Data["error"]=$error;//Assign error message to template
			$this->Data["code"]=$this->postParams["code"];
			$this->Data["name"]=$this->postParams["name"];
			$this->Data["email"]=$this->postParams["email"];
			$this->Template="activate.tpl";//Set template to current step
		}else{
			$user->email=$this->postParams["email"];
			$user->firstname=$this->postParams["name"];
			$this->m_oDB->update_entry($user,$userid);
			$this->Data["userID"]=$userid;
			$this->Data["code"]=$this->postParams["code"];
			$this->Template="password.tpl";
		}
	}
	public function step3(){
		$error="";
		$userID=$this->postParams["userID"];
		$usercode_challenge=$this->postParams["usercode_challenge"];
		$newusercode=$userID.$this->postParams["usercode"];
		$user=new user($usercode_challenge);
		if($user->ID()== $userID && !empty($userID) && !empty($this->postParams["usercode"])){
			$user->code=$newusercode;
			$user->custom_password="TRUE";
			$user->inactive="FALSE";
		}else{
			$error="Nieuwe code mag niet leeg zijn<br>";
			$this->Data["code"]=$this->postParams["usercode_challenge"];
			$this->Data["userID"]=$userID;
			$this->Template="password.tpl";
		}
		$this->m_oDB->update_entry($user,$user->ID());
		$this->Data["daylimit"]=controller::conf('DAYLIMIT_BOOKING');
		$this->Data["prelimit"]=controller::conf('PRELIMIT_BOOKING');
		$this->Data["period"]=controller::conf('DAYS_PREBOOKING')+1;
		$this->Template="stap3.tpl";
		//gegevens even doormailen
		$message="";
        $error="";
        $mail       ="Beste ".$user->firstname.",<br><br>";
        $mail       .="Je <i>peliwash</i> account is nu geactiveerd. Hieronder staan je gegevens.<br><br>";
        $mail       .="Gebruikerscode: <b>".$user->code."</b><br><br>";
        $mail       .="Je kunt nu reserveren door op een vrije plaats in het overzicht op <a href='www.peliwash.nl'>peliwash.nl</a> te klikken.";
        $mail       .="<br><br>Met vriendelijke groet,<br><br>";
        $mail       .="Student Beheer peliwash";
        $mailer=new peliwashmail("internet", $user->email);
        $mailer->subject="peliwash | Activeren voltooid";
        $mailer->message=$mail;
        $mailer->send();
	}
}
?>