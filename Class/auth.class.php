<?php
/**
 * Description of authclass
 *
 * @author jj
 */
class auth {
    var $userID;
    
    public function __construct($userID){
        $this->userID       = $userID;
    }

    public function check_user(){
        if(!empty($this->userID) && !in_array($this->userID,$GLOBALS["BANNED_USERS"])){
            return TRUE;
        }else{
            return FALSE;
        }
    }

}
?>
