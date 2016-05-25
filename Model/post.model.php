<?php
class model_post{
    var $Data;
	var $getParams;
	var $m_oDB;
	var $Template;
	var $ClearCache;
	var $postParams;
	var $action;
	
    public function  __construct($postParams,$getParams,$action="") {
        $this->m_oDB        = new db();
        $this->Data    		= array();
		$this->Template		= "";
		$this->getParams	= $getParams;
		$this->postParams	= $postParams;
		if(!empty($action)){
			$this->action=$action;
		}else{
			$this->action=$_GET["action"];
		}
		$this->ClearCache	= TRUE;
		$this->NoCache		= TRUE;
		$this->getData();
	}
	public function getData(){
		switch($this->action){
			default:
				//List all posts
				$posts=array_reverse($this->m_oDB->listall("post"));
				foreach($posts as $post){
					$this->Data[$post["category"]][]=$post;
				}
				$this->Template="home/posts.tpl";
				break;
			case "singlepost":
				//List a single post
				$request=$_GET["q"];
				$data=$this->m_oDB->entry_by_property("post","ID",$request);
				$post=$data[0];
				$this->Data["post"]=$post;
				$this->Template="home/singlepost.tpl";
				break;
			case "viewcat":
				//List all posts from a category
				$request=$_GET["q"];
				$this->Data["posts"]=array_reverse($this->m_oDB->entry_by_property("post","category",$request));
				$this->Template="home/catposts.tpl";
				break;
			case "add":
				$this->add();
				$Data="Bericht is toegevoegd";
				break;
			case "addform":
				$Data=$this->addform();
				break;
			case "delete":
				$this->delete();
				$Data="Bericht is verwijderd";
				break;
			
		}
	}
	public function listall(){

	}
}


?>