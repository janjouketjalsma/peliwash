<?php
class model_booking{
    var $Data;
	var $getParams;
	var $m_oDB;
	var $Template;
	var $ClearCache;
	var $postParams;
	
    public function  __construct($postParams,$getParams) {
        $this->m_oDB        = new db();
        $this->Data    		= array();
		$this->Template		= "";
		$this->getParams	= $getParams;
		$this->postParams	= $postParams;
		$this->ClearCache	= FALSE;
		$this->NoCache		= TRUE;
		$this->getData();
    }
	public function getData(){
		if(!empty($this->postParams["usercode"])){
			$this->makeBooking();
		}else{
			$this->displayForm();
		}
	}
	public function displayForm(){
		$date		= $this->getParams["date"];
		$time		= $this->getParams["time"];
		if(empty($this->getParams["date"])){
			$info=explode("|",$this->getParams["book"]);
			$date=$book[0];
			$time=$book[1];
		}
		$slot		= new slot($date,$time);
		$slotdata	= $slot->output();
		if($slotdata["status"]=="available"){
			$this->Data["dateNL"]			= dbviews::date2nl($date);
			$this->Data["date"]				= $date;
			$this->Data["time"]				= $time;
			$this->Data["machines"]			= $slotdata["available_machines"];
			$this->Data["selected_machines"]= array_slice($slotdata["available_machines"],0,1);
			$this->Template					= "booking.tpl";
		}else{
			echo "slot bezet <a href=?page=home>keer terug</a>";
		}
	}
	
	public function makeBooking(){
		$this->ClearCache	= TRUE;//Make sure cache gets cleared after booking
		$user				= new user($this->postParams["usercode"]);
		//Set vars
		$userID				= $user->ID();
		$date				= $this->postParams["date"];
		$time				= $this->postParams["time"];
		if(empty($this->postParams["bookings"])){
		   $error.="Geen machines aangevinkt<br>";
		}elseif(empty($userID)){
		   $error.="Gebruikercode onbekend, reservering mislukt<br>";
		}elseif(!$user->allowed($date,$time,$_POST["bookings"])=="yes"){
		   $error.="Gebruik is niet toegestaan wegens gebruikersquota of je account is nog niet geactiveerd.<br><br>Klik <a class='white' href=?page=activate>hier</a> 
			   om te activeren als je dat nog niet gedaan hebt.";
		}else{
			//Make each booking
			$slot		= new slot($date,$time);
			$slotdata	=$slot->output();
			$i=1;
			foreach($this->postParams["bookings"] as $machine){   
				if(!in_array($machine,$slotdata["available_machines"])){
					$error.="Gekozen machines niet (meer) beschikbaar<br>";
					break;
				}else{
					$booking=new booking($date,$time);
					$booking->m_sOwner=$userID;
					$booking->m_sMachine=$machine;
					$booking_id[$machine]=$this->m_oDB->new_entry($booking);
					$booking_security[$machine]=$booking->m_sSecurity;
				}
				$i++;
			}
			//////////////////////////
			//Add to google calendar button
			////////////////////////////
			$timesplitter       =explode(":",$time);
			$starthour          =$timesplitter[0];
			$startminute        =$timesplitter[1];
			$finishtime         =date("H:i", strtotime($time)+(75*60));
			$timesplitter2      =explode(":",$finishtime);
			$finishhour         =$timesplitter2[0];
			$finishminute       =$timesplitter2[1];

			$format=controller::conf("DATEFORMAT")." Y";
			$date=$date." ".date("Y");
			$bookingdate = DateTime::createFromFormat($format,$date);
			if(date("I",$bookingdate->getTimestamp())==1){
				//Zomertijd
				$interval=2;
			}else{
				//Wintertijd
				$interval=1;
			}
			$bookingdate->setTime($starthour,$startminute);
			$bookingdate->sub(new DateInterval('PT'.$interval.'H'));
			$gcaldateStart=$bookingdate->format("Ymd\THi");
			$bookingdate->setTime($finishhour,$finishminute);
			$bookingdate->sub(new DateInterval('PT'.$interval.'H'));
			$gcaldateFinish=$bookingdate->format("Ymd\THi");
			$gcalmessage='<br><a href="http://www.google.com/calendar/event?action=TEMPLATE&text=Wassen%20';
			foreach ($this->postParams["bookings"] as $machine){
				$gcalmessage.=$machine."%20";
			}
			$gcalmessage.='&dates='.$gcaldateStart.'00Z/'.$gcaldateFinish.'00Z&details=&location=Wasruimte%20peliwash&trp=true&sprop=http%3A%2F%2Fpeliwash.nl&sprop=name:Wassen%20%7C%20peliwash" target="_blank"><img title="toevoegen aan Google Calendar" alt="Toevoegen aan Google Calendar" src="http://www.google.com/calendar/images/ext/gc_button2_nl.gif" border=0></a><br>';
			////////////////////////////////EINDE GCAL
			$message.="Reservering(en) gemaakt<br>";
		}
		if(!empty($error)){
			$this->Data["error"]=$error;
			$this->Template="bookingFailed.tpl";
		}else{
			$this->Data["emailmessage"]=$this->sendMail($gcalmessage,$booking_id,$booking_security);
			$this->Data["gcal"]=$gcalmessage;
			$this->Data["message"]=$message;
			$this->Data["dateNL"]			= dbviews::date2nl($date);
			$this->Data["date"]				= $date;
			$this->Data["time"]				= $time;
			$this->Data["machines"]			= $this->postParams["bookings"];
			$this->Template="bookingConfirmed.tpl";
		}
	}
	private function sendMail($gcalmessage,$bid,$bsec){
		include_once("Functions/mail.php");
		$message="";
        $error="";
        //Send confirmation mail
        $user=new user($this->postParams["usercode"]);
        $mail       ="Beste ".$user->firstname.",<br><br>";
        $mail       .="Bedankt voor je reservering op ".dbviews::date2nl($this->postParams['date'])." om ".$this->postParams['time'].". We hebben je staan voor de wasmachines: ";
        foreach($this->postParams["bookings"] as $machine){
            $machines.= $machine." (<a href='http://www.peliwash.nl/?page=cancel&id=".$bid[$machine]."&check=".$bsec[$machine]."'>annuleren</a>), ";
        }
        $mail       .=rtrim($machines,", ");
        $mail       .=".";
        $mail       .="<br>".$gcalmessage;
        $mail       .="<br><b>BEWAAR DEZE MAIL GOED</b> deze email is je reserveringsbevestiging</b>";
        $mail       .="<br><br>Met vriendelijke groet,<br><br>";
        $mail       .="Student Beheer peliwash";
        $subject    ="peliwash | Reservering voor wassen";
        $mailer=new peliwashmail("internet",$user->email);
        $mailer->subject=$subject;
        $mailer->message=$mail;
        if($mailer->checkreceipent()){
            $message="Email verstuurd<br>";
        }else{
            $error="Email versturen mislukt, emailadres onjuist<br>";
        }
        $mailer->send();
        return array($message,$error);
	}
}
?>