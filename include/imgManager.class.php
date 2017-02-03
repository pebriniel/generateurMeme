<?php

class imageManager{
	
	private $loadOriginal = false;
	private $file = null;
	private $url = null;
	private $ext = null;
	private $outputDirectory = "./";
	private $outputName = "undefined";
	private $DefaultFont = "font/impact.ttf";
	private $DefaultTextAngle  = 0;

	private $debug = false;
	private $erreur = "";
	
	function __construct($url = "./"){
		$this->url = $url;
	}
	
	public function loadFile($url = null){
		if(!is_null($this->url) || !is_null($url)){
			if(!is_null($url)){
				$this->url = $url;
			}
			
			$this->setExtension();	
			
			if($this->url[0] == '.' || $this->url[0] == '/' || $this->url[0] != 'h' || $this->url[0] != 'f'){
				if(file_exists($this->url)){
					$this->loadOriginal = true;
				}
			}
			else{
				$ch = curl_init($this->url);    
				curl_setopt($ch, CURLOPT_NOBODY, true);
				curl_exec($ch);
				if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200){
					$this->loadOriginal = true;			
				}
			}
			
			if($this->loadOriginal){
				$this->loadImg();	
				$this->loadDetail();
			}
		}
	}
	
	public function setFolderUpload($fold = null){
		if(!is_null($fold)){
			$this->outputDirectory = $fold;
		}
	}		
	
	public function setNameFileUpload($name = null){
		if(!is_null($name)){
			$this->outputName = $name;
		}
	}
	
	public function resize($x = 100, $y = 100, $name = null){
		if(is_null($name)){
			$name = $this->outputName;
		}
		
		$new_image = imagecreatetruecolor($x, $y);
		imagecopyresampled($new_image, $this->loadOriginal, 0, 0, 0, 0, $x, $y, $this->getDetail('width'), $this->getDetail('height'));
		$this->generateImg($new_image, $this->outputDirectory.$name.".".$this->ext);
	}
	
	public function createImg($name = null){
		if(is_null($name)){
			$name = $this->outputName;
		}
		
		if($this->fileLoaded()){
			$this->generateImg($this->loadOriginal, $this->outputDirectory.$name.".".$this->ext);
		}
	}
	
	//on crée une nouvelle image
	private function generateImg($img, $name = null){
		if($this->fileLoaded()){	
			switch($this->ext){
				case 'IMAGETYPE_GIF':
				case 'gif':
					imagegif($img, $name);
				break;
				case 'IMAGETYPE_JPG':
				case 'IMAGETYPE_JPEG':
				case 'jpg':
				case 'jpeg':
				case 2:
					imagejpeg($img, $name);
				break;
				case 'IMAGETYPE_PNG':
				case 'png':
					imagepng($img, $name);
				break;
				default: 
					$this->err[] = "generateImg() :: Le format n'est pas supporté<br/>";
				break;
			}
		}
	}
	
	
	private function loadImg(){
		if($this->fileLoaded()){
			switch($this->ext){
				case 'IMAGETYPE_GIF':
				case 'gif':
					$this->loadOriginal = imagecreatefromgif($this->url);
				break;
				case 'IMAGETYPE_JPG':
				case 'IMAGETYPE_JPEG':
				case 'jpg':
				case 'jpeg':
					$this->loadOriginal = imagecreatefromjpeg($this->url);
				break;
				case 'IMAGETYPE_PNG':
				case 'png':
					$this->loadOriginal = imagecreatefrompng($this->url);
				break;
				default: 
					$this->err[] = "loadImg() :: Le format n'est pas supporté<br/>";
				break;
			}
		}
	}
	
	/* a supprimé ?? */
	private function getExt(){
		$extension  = array(
			IMAGETYPE_GIF => "gif",
			IMAGETYPE_JPEG => "jpeg",
			IMAGETYPE_PNG => "png",
			IMAGETYPE_BMP => "bmp",
			IMAGETYPE_ICO => "ico"
		);

		return $extension[$this->ext];
	}
	
	public function setExtension($ext = null){
		if(is_null($this->ext)){
			if(is_null($ext)){
				$this->ext = substr($this->url, strrpos($this->url, '.') + 1);
				$this->dataObjet['ext'] = $this->ext;
			}
			else{
				$this->ext = $ext;	
				$this->dataObjet['ext'] = $this->ext;
			}
		}
	}
	
	public function getDetail($key = null){
		if(is_null($key)){
			return $this->dataObjet;
		}
		else{
			return $this->dataObjet[$key];			
		}
	}
	
	private function loadDetail(){
		if($this->fileLoaded()){
			list($this->dataObjet['width'], $this->dataObjet['height'], $this->dataObjet['type'], $this->dataObjet['attr']) = getimagesize($this->url);
		}
	}
	
	
	
	public function fileLoaded(){
		return $this->loadOriginal;
	}

	
	/**
     * Place a text on the image
     * 
	 * @param string 	$key	the key of array
     * @param string	$text 	the text to place
     * @param int 		$x 		the X coordinate where to place the text
     * @param int 		$y 		the Y coordinate where to place the text
     * @param int 		$size 	the size of the text (optional)
     * @param string 	$color 	the color of the text in haxadecimal (#CC0000 or CC0000) (optional)
     * @param string 	$font 	the text font (optional)
     * @param int 		$angle 	the angle of the text (optional)
     * 
     * @return array   	Returns an array with 8 elements representing four points making the bounding 
     *					box of the text. The order of the points is lower left, lower right, upper 
     *					right, upper left
     * 					! Note take care, could return False on error... Thks PHP
     * 
     * @access public
     * 
     */
			
	public function addText($key, $text = "empty", $x = 0, $y = 0, $size = 50, $color = '#7fff00', $font = NULL, $angle = NULL){
		$this->textKey[] = $key;
		$this->text[$key]['texte'] = $text;
		$this->text[$key]['posx'] = $x;
		$this->text[$key]['posy'] = $y;
		$this->text[$key]['size'] = $size;
		$this->text[$key]['color'] = $color;

		if(is_null($font) || !file_exists('font/'.$font.'.ttf'))
		{
			$this->text[$key]['font'] = $this->DefaultFont;
		} else {
			$this->text[$key]['font'] = 'font/'.$font.'.ttf';
		}
		
		if(is_null($angle) && !is_numeric($angle)){
			$this->text[$key]['angle'] = $this->DefaultTextAngle;
		}
		else{
			$this->text[$key]['angle'] = $angle;
		}
	}
	
	public function generateText($key = null){
		if(is_null($key)){
			
			for($i = 0; $i < sizeof($this->textKey); $i ++){
				$this->createText($this->text[$this->textKey[$i]]);
			}
		}
		else{
			$this->createText($this->text[$key]);
		}
	}
	
	private function createText($val){
		$color = $this->hex2rgba($val['color']);
		$color = imagecolorallocate($this->loadOriginal, $color[0], $color[1], $color[2]);
	
		$font = array('http://boussads.student.codeur.online/meme/font/impact.ttf');
		
		imagettftext($this->loadOriginal, $val['size'], $val['angle'], $val['posx'], $val['posy'], $color, $val['font'], $val['texte']);	
	
	}
	
	
	private function hex2rgba($color){
		$default = '0,0,0';

		//Return default if no color provided
		if(empty($color))
			  return $default; 

		//Sanitize $color if "#" is provided 
		if ($color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if (strlen($color) == 6) {
				$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
				$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb =  array_map('hexdec', $hex);
		
		return $rgb;
	}
}

?>