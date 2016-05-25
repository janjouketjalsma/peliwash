<?php
class model_home{
	var $Data;
	var $Template;
        var $getParams;
        var $postParams;
        var $NoCache;
        var $ClearCache;
    public function  __construct($postParams,$getParams) {
        $this->m_oDB   = new db();
        $this->Data    = array();
        $this->getParams=$getParams;
        $this->postParams=$postParams;
        $this->NoCache=FALSE;
        $this->ClearCache=FALSE;
		$this->Template="home.tpl";
        $this->getData();
    }

    public function getData(){
        //error_reporting(E_ALL);
        include("Model/post.model.php");
        $postmodel=new model_post($this->postParams,$this->getParams);
        $view=new Smarty();
        $view->caching=0;
        $view->assign($postmodel->Data);
        $this->Data["peliwashposts"]=$view->fetch($postmodel->Template);
    }
}
?>
