<?php

	class uploadFile{
	
		
		function __construct($uplFold = "./", $ext = "jpg, png, bmp, gif"){
			$this->outUploadFold = $uplFold;
			$this->extension = $ext;
			$this->extensionFile = "unknown";
			$this->OutUploadName = "unknown";
			$this->mess = null;
			
			$this->extensionConfirme = false;
		}
		
		
		public function setKeyInput($key = "fileinput"){
			$this->keyInput = $key;
		}
		
		public function setNameFile($name = "unknown"){
			$this->OutUploadName = $name;
		}
		
		public function uploadFromInput($key = null){
			if(is_null($key)){ $key = $this->keyInput; }else{ $this->keyInput = $key; }
			
			
			if(is_uploaded_file($_FILES[$key]['tmp_name'])){
				$this->extensionFile = substr($_FILES[$key]['name'], strrpos($_FILES[$key]['name'], '.') + 1);
				
				$this->extensionConfirme = $this->verification_Extension();
			}
		}
		
		public function addExtension($ext = null){
			if(!is_null($ext)){
				$this->extension .= $ext;
			}
		}
		
		public function getExtension(){
			return $this->extensionFile;
		}
		
		public function verification_Extension(){
			//j'envoie le fichier je vérifie si le format correponds a TRUC

			$extList = explode(',', $this->extension);
			
			if(!in_array($this->extensionFile, $extList)){
				$this->mess[] = "l'extension n'est pas bonne";
				return false;
			}
			else{
				$this->mess[] = "l'extension est bonne";
				return true;
			}
		}
		
		
		public function upload(){
			if($this->extensionConfirme){
				move_uploaded_file($_FILES[$this->keyInput]['tmp_name'], $this->outUploadFold.$this->OutUploadName.".".$this->extensionFile);
				return true;
			}
			else{
				return false;
			}
		}
		
		
	}

