<?php

class getUrl{

	function __construct(){
		Mustache_Autoloader::register();
		
		$this->keyView = 2;
		$this->param = explode('/', $_SERVER['REQUEST_URI']);
		$this->actionExist = false;

		$this->aHeader = array();

		$this->options =  array('extension' => '.html');

		$this->m = new Mustache_Engine(array(
		  'loader' => new Mustache_Loader_FilesystemLoader('template/themeA', $this->options),
		));
	}
	
	function arrayHeader($key, $val){
		if(is_array($key)){
			array_merge($this->aHeader, $key);
		}
		else{
			$this->aHeader[$key] = $val;
		}
	}
	
	function exec($val){
		$val($this->getParam(PARAM_ID));
	}
	
	function getParam($key){
		return $this->param[$key];
	}
	
	
	public function get($action = null){
		if(!is_null($action)){
			if($this->param[$this->keyView] == $action){
				$this->actionExist = true;
				return true;
			}
			else if($action == "/"){
				$this->actionExist = true;
				return true;
			}
			else{
				$this->actionExist = false;
				return false;
			}
		}
	}
	
	public function loadTemplate($text = "inconnu", $exec = null){
		if($this->actionExist){
			echo $this->m->render('header', $this->aHeader);
			echo $this->m->render($text, $exec);
		}
	}
	

}

?>