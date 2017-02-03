<?php
	

$sizeTop = request('sizeTop');
$sizeBot = request('sizeBot');
$clrTop = request('clrTop');
$clrBot = request('clrBot');
$textTop = request('textTop');
$textBot = request('textBot');
$idImg = request('idImg');
$fonctionAppel=request('fonctionAppel');

/*-------------------------------*/
			/*DEBUG*/
/*-------------------------------*/
/*$sizeTop = 30;
$sizeBot = 30;*/
$clrHexaBot=$clrBot;
$clrHexaTop=$clrTop;
$clrBot =  hex2rgba($clrBot);
$clrTop =  hex2rgba($clrTop);


//http://yohannc.student.codeur.online/meme/include/fontionGeneImage.php?fonctionAppel=1&sizeTop=30&sizeBot=30&clrTop=%23000000&clrBot=%23000000&textTop=rea&textBot=reza&idImg=1
/*-------------------------------*/
 

//if($idImg && $sizeTop && $sizeBot && $clrTop && $clrBot && ($textTop && $textBot)  ){
	//ob_start();

	$infoImg = recupImgSource($idImg);
	$nomNouvImg = creerImage($infoImg,$textTop,$textBot,$clrTop,$clrBot,$sizeTop,$sizeBot, false);
	echo base64_encode(file_get_contents("../public/tmp/".md5($_SERVER['REMOTE_ADDR']).$infoImg['type']));
	unlink("../public/tmp/".md5($_SERVER['REMOTE_ADDR']).$infoImg['type']);
// }
// else{
// 	echo "wahoo";
// 	echo "remplissez tous les champs";
//}
