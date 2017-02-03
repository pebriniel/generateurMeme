<?php

	/*
		[SERVER_PROTOCOL] => HTTP/1.1
		[REQUEST_METHOD] => GET
		[QUERY_STRING] => 
		[REQUEST_URI] => /class/
		[SCRIPT_NAME] => /class/index.php
		[PHP_SELF] => /class/index.php
		[REQUEST_TIME] => 1482222172
	*/
	
	define('PARAM_ID', 3);
	define('URL_SITE', 'http://boussads.student.codeur.online/memeold/');

	require_once('get.class.php');
	require_once('pdo.class.php');
	
	include "include/Mustache/Autoloader.php";
	
	require_once('include/imgManager.class.php');
	require_once('include/imgMeme.class.php');
  
	$db = new SQLpdo("boussads", "boussads", "JqsWM87PwX", "127.0.0.1");
	$app = new getUrl();
	$app->arrayHeader('URL', URL_SITE);
	
	//le generateur 
	if($app->get('generator')){
		if(isset($_REQUEST['generate'])){
			$sizeTop = $_REQUEST['sizeTop'];
			$sizeBot = $_REQUEST['sizeBot'];
			$clrTop = $_REQUEST['clrTop'];
			$clrBot = $_REQUEST['clrBot'];
			$textTop = $_REQUEST['textTop'];
			$textBot = $_REQUEST['textBot'];
			$idImg = $_REQUEST['idImg'];
			
			$request = $_REQUEST['idImg'];
			if($request[0] == "h"){
				$url = $request;
			}
			else{
				$v = $db->fetch("SELECT * FROM `memeimage` WHERE ID= :id", array(':id' => $request));
				$url = 'public/img/'.$v['ID'].'.'.$v['type'];
			}
			
			$meme = new generateMeme($url);
			$id_file = uniqid();
			
			$id_bdd = $db->insert("INSERT INTO memegenerate (url, ID_type) VALUES(:url, :type)", array(':url' => $id_file, ':type' => $meme->getDetail('ext')));
			
			$meme->setMemeNameFile($id_file);
			$meme->textTop($_REQUEST['textTop'], $_REQUEST['clrTop']);
			$meme->textBot($_REQUEST['textBot'], $_REQUEST['clrBot']);
			$meme->generatMeme('public/uploadImg/', true);
			
			header('location:'.URL_SITE.'vue/'.$id_bdd);
		}
		
		if($id = $app->getParam(PARAM_ID)){
			$v = $db->fetch("SELECT * FROM `memeimage` WHERE ID= :id", array(':id' => $id));
			$view = array('SHOW' => true, 'URL_SITE' => URL_SITE, 'URL' => URL_SITE.'public/img/','ID' => $v['ID'], 'FORMAT' => $v['type']);
		}
		else{
			$view = array('SHOW' => false);
		}
		
		$app->loadTemplate('creation', $view);
	}
	
	//la vue
	else if($app->get('liste')){
		
		$v = $db->fetchAll("SELECT memegenerate.ID AS ID, memegenerate.url AS IDGenerate, CASE WHEN memegenerate.ID_memeImage IS NULL THEN memegenerate.ID_type ELSE memeimage.type END AS FORMATIMG FROM `memegenerate` LEFT JOIN memeimage ON memeimage.ID = memegenerate.ID_memeImage ORDER BY memegenerate.ID DESC");
		$app->loadTemplate('liste', array('SHOW' => true, 'URL' => URL_SITE, 'LIST' => $v));
	}
	else if($app->get('vue')){
		if($id = $app->getParam(PARAM_ID)){
			$v = $db->fetch("SELECT *, memegenerate.ID AS IDGenerate  FROM `memegenerate` LEFT JOIN memeimage ON memeimage.ID = memegenerate.ID_memeImage WHERE memegenerate.ID = :id ", array(':id' => $id));
			if($v['ID_type'] == 'null' || is_null($v['ID_type']) || $v['ID_type'] == ''){
				$type = $v['type'];
			  }
			  else{
				$type = $v['ID_type'];
			  }
			$view = array('SHOW' => true, 'URL' => URL_SITE.'public/uploadImg/'.$v['url'].'.'.$type);
		}
		else{
			$view = array('SHOW' => false);
		}
		$app->loadTemplate('vue', $view);
	}
	else if($app->get('ajaxRefreshImage')){
		
		$image_name = uniqid();
		if(isset($_GET['idImg'])){
			$request = $_REQUEST['idImg'];
			if($request[0] == "h"){
				$url = $request;
			}
			else{
				$v = $db->fetch("SELECT * FROM `memeimage` WHERE ID= :id", array(':id' => $request));
				$url = 'public/img/'.$v['ID'].'.'.$v['type'];
			}
			
			$meme = new generateMeme($url);
			$meme->setMemeNameFile(md5($_SERVER['REMOTE_ADDR']));
			$meme->textTop($_REQUEST['textTop'], $_REQUEST['clrTop'], $_REQUEST['sizeTop']);
			$meme->textBot($_REQUEST['textBot'], $_REQUEST['clrBot'], $_REQUEST['sizeBot']);
			$meme->generatMeme('public/tmp/', true);
			
			echo base64_encode(file_get_contents("public/tmp/".md5($_SERVER['REMOTE_ADDR']).".".$meme->getDetail('ext')));
		}
	}
	//l'index 
	else if($app->get('/')){
		
		$val = $val = $db->fetchAll("SELECT * FROM `memeimage`");
		$app->loadTemplate('main', array('list' => $val));
	}

?>