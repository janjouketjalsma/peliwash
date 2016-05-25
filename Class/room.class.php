<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of roomclass
 *
 * @author Beheer Pelikaanhof
 */
class room{
    private $ID;
    var $userID;
    var $entrance;
    var $floor;
    var $roomsize;
    var $roomtype;
    var $hallway;
    var $affix;
    var $kitchens;
    var $bathrooms;
    var $toilets;
    var $postalcode;
    var $location;
    var $roomnumber;
    var $linkedto;
    private $data;
    public function __construct($ID=""){
        if(!empty($ID)){
            $this->ID=$ID;
        }
        $this->roomnumber="";
        $this->affix="-";
        $this->roomtype="";
        $this->roomsize="";
        $this->userID="";
        $this->floor="";
        $this->hallway="";
        $this->entrance="";
        $this->location="";
        $this->kitchens="";
        $this->bathrooms="";
        $this->postalcode="";
        $this->toilets="";
        $this->data="";
        //$this->calculate();
    }
    public function roomtype($hallway){
        $appartementen=array(3,9,17,25,33,36,41,42,79,102,109,116,123,141,140,147,148,154,155,160,161);
        $anders=array(51,52,136);
        if(in_array($hallway,$appartementen)){
            $this->roomtype="Appartement";
        }elseif(in_array($hallway,$anders)){
            $this->roomtype="Bedrijf/Stichting";
        }else{
            $this->roomtype="Kamer";
        }
    }

    public function import($filepath,$current=0){
        $xml=simplexml_load_file($filepath);
        foreach($xml->Combiquery as $roomxml){
            $room=new room();
            $room->hallway=trim((string)$roomxml->Nummer);
            $room->roomtype($room->hallway);
            $room->affix=trim((string)$roomxml->KamerNummer);
            $room->bathrooms=trim((string)$roomxml->AantalDouches);
            switch((string)$roomxml->Opgang){
                case 1:
                    $room->entrance="A";
                    break;
                case 2:
                    $room->entrance="B";
                    break;
                case 3:
                    $room->entrance="C";
                    break;
                case 4:
                    $room->entrance="D";
                    break;
            }
            $room->linkedto=trim((string)$roomxml->VerlengdeGang);
            $room->kitchens=trim((string)$roomxml->AantalKeukens);
            $room->postalcode=trim((string)$roomxml->Postcode);
            $room->location=trim((string)$roomxml->Omschrijving);
            $room->roomsize=trim((string)$roomxml->Oppervlakte);
            $room->floor=trim((string)$roomxml->Verdieping);
            $room->roomnumber=$room->hallway.$room->affix;
            $deze=str_pad($room->roomnumber,4,"0", STR_PAD_LEFT);
            $this->data[$deze]=$room;
            unset($room);
        }
        //$db=new db();
        //$db->entry_by_property("user","roomnumber",$currentroom);
        $current++;
        //$this->import($filepath,$current);
    }
    public function ReturnData(){
        //print_r($this->data["006a"]);
        return $this->data;
    }
}
?>
