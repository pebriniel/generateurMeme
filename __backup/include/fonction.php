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
/*$sizeTop = request('sizeTop');
$sizeBot = request('sizeBot');
$clrTop = request('clrTop');
$clrBot = request('clrBot');*/
$textTop = request('textTop');
$textBot = request('textBot');
$idImg = request('idImg');

/*-------------------------------*/
			/*DEBUG*/
/*-------------------------------*/
$sizeTop = 30;
$sizeBot = 30;
$clrTop ="fffff";
$clrBot = "ffff";


/*-------------------------------*/


if($idImg && $sizeTop && $sizeBot && $clrTop && $clrBot && $textTop && $textBot  ){
	
	$infoImg = recupImgSource($idImg);
	$nomNouvImg = creerImage($infoImg,$textTop,$textBot,$clrTop,$clrBot,$sizeTop,$sizeBot);
	$idNouvGen = insertImg($nomNouvImg,$textTop,$textBot,$clrTop,$clrBot,$sizeTop,$sizeBot,$infoImg['ID']);
	header('location:../index.php?action=vue&id='.$idNouvGen);
}
else{
	echo "remplissez tous les champs";
}

function request($key){
	 /*&& !empty(trim($_REQUEST[$key]))*/
	if(isset($_REQUEST[$key])){
		return $_REQUEST[$key];
	}else{
		return false;
	}

}
function recupImgSource($idImg){
	$sql= new SQLpdo();
	$info=$sql->fetch("SELECT * FROM `memeImage` WHERE ID=:idImg", array(":idImg" => $idImg));
	return $info;

}


function creerImage($info,$texteTop,$textBot,$clrTop,$clrBot,$sizeTop,$sizeBot){
	$font=5;
	$idUnique=uniqid();
	//$font=loadFonts('myfont');
	
	$monImage = EMPL_ORIGNAL.$info['ID'].".".$info['type'];
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
	$colorTop = imagecolorallocate($image,255,255,255);
	imagestring($image, $font, 0, 0,$texteTop,$colorTop);
	imagestring($image, $font, 150, 150,$textBot,$colorTop);

	switch ($info['type']) {
		case 'jpeg':
		case 'jpg':
			imagejpeg($image,EMPL_UPL.$idUnique.".".$info['type']);
			break;
		case 'png':
			imagepng($image,EMPL_UPL.$idUnique.".".$info['type']);
			break;
		case 'gif':
			imagegif($image,EMPL_UPL.$idUnique.".".$info['type']);
			break;
		case 'svg':
			
			break;
		
		default:
			# code...
			break;
	}
	return $idUnique;

}

function loadFonts($nameFont){
	switch ($nameFont) {
		case 'myfont':
			$font=imageloadfont('fonts/impact.ttf');

			break;
		
		default:
			# code...
			break;
	}
	

	return $font;
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

function insertImg($img,$textTop,$textBot,$clrTop=null,$clrBot=null,$sizeTop=null,$sizeBot=null,$id){
	
	$sql= new SQLpdo();
	$idGen=$sql->insert("INSERT INTO `memeGenerate` ( url, textTop, textBot, clrTop, clrBot, sizeTop, sizeBot, ID_memeImage) VALUES ( :url, :textTop, :textBot, :clrTop, :clrBot, :sizeTop, :sizeBot, :ID_memeImage)",
		array(":url" => $img,':textTop'=> $textTop, ':textBot'=> $textBot, ':clrTop'=> $clrTop, ':clrBot'=> $clrBot, ':sizeTop'=> $sizeTop, ':sizeBot'=> $sizeBot, ':ID_memeImage'=> $id ));

	

	return $idGen;
}



