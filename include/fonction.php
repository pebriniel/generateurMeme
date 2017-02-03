<?php

require_once ".init.php";
/*Pour générer une image on a besoin de :
$sizeTop; taille de la police du haut.
$sizeBot; taille de la police du bas.
$clrTop; couleur de la police du haut.
$clrBot; couleur de la police du bas.
$textTop; text du haut.
$textBot; text du bas 
$idImg ; Id de l’image sélectionné.
 
*/


function request($key){
	 /*&& !empty(trim($_REQUEST[$key]))*/
	if(isset($_REQUEST[$key])){
		return $_REQUEST[$key];
	}else{
		return false;
	}

}
function recupImgSource($idImg){
	if($idImg[0] == "h"){		
		$info['type'] = substr(strrchr($idImg, "."), 1);
		$info['URL'] = $idImg;
		$info['GIPHY'] = true;
		$info['ID'] = null;

		return $info;
	}
	else{
		$sql= new SQLpdo();
		$info=$sql->fetch("SELECT * FROM `memeImage` WHERE ID=:idImg", array(":idImg" => $idImg));
		$info['GIPHY'] = false;
		$info['URL'] = null;
		return $info;
	}
}


function creerImage($info,$texteTop,$textBot,$clrTop,$clrBot,$sizeTop,$sizeBot, $enregistrer = true){
	$font='../fonts/impact.ttf';
	$idUnique=uniqid();

	if($enregistrer){
		$nameImage = EMPL_UPL.$idUnique.".".$info['type'];
	}
	else{
		$nameImage = "../public/tmp/".md5($_SERVER['REMOTE_ADDR']).$info['type'];
	}
	
	if($info['GIPHY']){
		$monImage = $info['URL'];
	}
	else{
		$monImage = EMPL_ORIGNAL.$info['ID'].".".$info['type'];
	}

	$IDimg = 1;
	switch ($info['type']) {
		case 'jpeg':
		case 'jpg': 
			//header ("Content-type: image/jpeg");
			$image = imagecreatefromjpeg($monImage);
			break;
		case 'png':
			
			$image = imagecreatefrompng($monImage);
			break;
		
		case 'gif':
			
			$image = imagecreatefromgif($monImage);
			break;
		case 'svg':
			return "Cas non géré pour le moment.";
			break;
		default:
			return "Cas non géré pour le moment.";
			break;
	}
	$x = imagesx($image);
	$y = imagesy($image);
	


	$colorTop = imagecolorallocate($image,$clrTop[0],$clrTop[1],$clrTop[2]);
	$colorBot = imagecolorallocate($image,$clrBot[0],$clrBot[1],$clrBot[2]);
	/*imagestring($image, $font, 0, 0,$texteTop,$colorTop);
	imagestring($image, $font, 150, 150,$textBot,$colorTop);*/
	$textBoxTop=imagettfbbox($sizeTop, 0, $font, $texteTop);
	$textWidthTop=$textBoxTop[2]-$textBoxTop[0];
	$textBoxBot=imagettfbbox($sizeBot, 0, $font, $textBot);
	$textWidthBot=$textBoxBot[2]-$textBoxBot[0];



	imagettftext($image, $sizeTop, 0, ($x/2)-($textWidthTop/2), ($sizeTop+10), $colorTop, $font, $texteTop);
	imagettftext($image, $sizeBot, 0, ($x/2)-($textWidthBot/2), ($y-20), $colorBot, $font, $textBot);

	switch ($info['type']) {
		case 'jpeg':
		case 'jpg':
			$_img = imagejpeg($image,$nameImage);
			break;
		case 'png':
			$_img = imagepng($image,$nameImage);
			break;
		case 'gif':
			$_img = imagegif($image,$nameImage);
			break;
		case 'svg':
			
			break;
		
		default:
			# code...
			break;
	}

	if($enregistrer){
		return $idUnique;
	}
	else{
		return $_img;
	}
}

class SQLpdo {
	function __construct(){

		try {
		    $this->db = new PDO('mysql:dbname='.DB.';host='.ADRESS.'',LOGIN,MDP);
		} catch (PDOException $e) {
		    echo 'Connexion échouée : ' . $e->getMessage();
		}

	}

	function fetchAll($sql,$execute=null){
		$sth = $this->db->prepare($sql);//prepare SQL request
	    $sth->execute($execute);//execute la requette sql
	    return $sth->fetchAll(PDO::FETCH_ASSOC);// recupère toutes les données
	}

	function insert($sql, $execute=null){
		$sth = $this->db->prepare($sql);//prepare SQL request
	    $sth->execute($execute);//execute la requette sql
	    return  $this->db->lastInsertId();// recupère toutes les données
	}

	function fetch($sql,$execute=null){
		$sth = $this->db->prepare($sql);//prepare SQL request
	    $sth->execute($execute);//execute la requette sql
	    return $sth->fetch(PDO::FETCH_ASSOC);// recupère toutes les données
	}
}

function insertImg($img,$textTop,$textBot,$clrTop=null,$clrBot=null,$sizeTop=null,$sizeBot=null,$id, $type){
	
	$sql= new SQLpdo();
	$idGen=$sql->insert("INSERT INTO `memeGenerate` ( url, textTop, textBot, clrTop, clrBot, sizeTop, sizeBot, ID_memeImage, ID_type) VALUES ( :url, :textTop, :textBot, :clrTop, :clrBot, :sizeTop, :sizeBot, :ID_memeImage, :type)",
		array(":url" => $img,':textTop'=> $textTop, ':textBot'=> $textBot, ':clrTop'=> $clrTop, ':clrBot'=> $clrBot, ':sizeTop'=> $sizeTop, ':sizeBot'=> $sizeBot, ':ID_memeImage'=> $id, ':type' => $type ));

	

	return $idGen;
}

function hex2rgba($color){
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


