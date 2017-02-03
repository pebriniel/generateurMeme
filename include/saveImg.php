<?php


	require_once('fonction.php');

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



	/*-------------------------------*/


	if($idImg && $sizeTop && $sizeBot && $clrTop && $clrBot && ($textTop|| $textBot)){
		echo "trololol";		
		$infoImg = recupImgSource($idImg);
		$nomNouvImg = creerImage($infoImg,$textTop,$textBot,$clrTop,$clrBot,$sizeTop,$sizeBot);
		$idNouvGen = insertImg($nomNouvImg,$textTop,$textBot,$clrHexaTop,$clrHexaBot,$sizeTop,$sizeBot,$infoImg['ID'], $infoImg['type']);
		header('location:../index.php?action=vue&id='.$idNouvGen);

	}
	else{
		echo "lol ?";
	/*	echo "remplissez tous les champs";*/
	}