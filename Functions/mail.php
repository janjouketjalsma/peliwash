<?php
class peliwashmail{
    var $destination;
    var $receipent;
    var $sender;
    var $subject;
    var $message;

    public function __construct($destination,$receipent){
        $this->destination      = $destination;
        $this->receipent        = $receipent;
        $this->subject          = "";
        $this->message          = "";
    }

    public function checkreceipent(){
        switch($this->destination){
            case "internet":
                return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $this->receipent);
                break;
            default:
                return FALSE;
        }
    }

    public function send(){
        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: peliwash <wassen@peliwash.nl>'. "\r\n";
        if(mail($this->receipent, $this->subject, $this->message,$headers)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}

?>
