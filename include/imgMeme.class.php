<?php

class generateMeme extends imageManager{
	
	function __construct($load = null){
		$this->setMemeNameFile();	
		$this->outputDirectory = "./public/uploadImg/";
		$this->setFolderUpload($this->outputDirectory);
		
		$this->load = false;
		
		if(!is_null($load) && !empty(trim($load))){
			$this->loadFile($load);
			$this->load = true;
		}
		
	}
	
	public function setMemeNameFile($name = "unknown"){
		$this->nameFile = $name;
	}	
	
	public function getMemeNameFile(){
		return $this->nameFile;
	}	
	
	public function textTop($content = "texte par default", $couleur = '#7fff00', $size = 30){
		$this->addText('textTop', $content, 0, 50, $size, $couleur);
	}
	
	public function textBot($content = "texte par default", $couleur = '#7fff00', $size = 30){
		$this->addText('textBot', $content, 10, $this->dataObjet['height'] - 10, $size, $couleur);
	}
	
	public function textPositionCustom($key, $content = "texte par default", $couleur = '#7fff00', $posx = 0, $posy = 70){
		$this->addText($key, array('text' => $content, 'posx' => $posx, 'posy' => $posy, 'color' => $couleur));
	}
	
	
	public function generatMeme($fold = './public/uploadImg/', $geneText = false){
		if($geneText){
			$this->generateText();
		}
		
		$this->setFolderUpload($fold);
		$this->createImg($this->getMemeNameFile());
	}
	
	public function generatMemeResize($x, $y){
		$this->resize($x, $y, $this->getMemeNameFile());
	}
	
}

?>