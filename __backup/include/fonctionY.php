<?php

fonction creerImage($monImage,$type,$texteTop,$textBot,$clrTop,$clrBot,$sizeTop,$sizeBot){
	$font=loadFonts('myfont');
	$x=imagesx($monImage);
	$y=imagesy($monImage);
	

	switch ($type) {
		case 'jpeg':
			header ("Content-type: image/jpeg");
			$image = imagecreatefromjpeg($monImage);
			break;
		case 'png':
			header ("Content-type: image/png");
			$image = imagecreatefrompng($monImage);
			break;
		case 'jpg':
			header ("Content-type: image/jpeg");
			$image = imagecreatefromjpeg($monImage);
			break;
		case 'gif':
			header ("Content-type: image/gif");
			$image = imagecreatefromgif($monImage);
			break;
		case 'svg':
			return "Cas non géré pour le moment."
			break;
		default:
			return "Cas non géré pour le moment."
			break;
	}

	imagestring($image, $font, 0, 0,$texteTop, $clrTop);

	imagestring($image, $font, 150, 150,$textBot, $clrBot);

	switch ($type) {
		case 'jpeg':
			imagejpeg($image,"public\uploadImg'\'"+$IDimg+"."+$type);
			break;
		case 'png':
			imagejpeg($image,"public\uploadImg'\'"+$IDimg+"."+$type);
			break;
		case 'jpg':
			imagejpeg($image,"public\uploadImg'\'"+$IDimg+"."+$type);
			break;
		case 'gif':
			imagejpeg($image,"public\uploadImg'\'"+$IDimg+"."+$type);
			break;
		case 'svg':
			
			break;
		
		default:
			# code...
			break;
	}
	

}

fonction loadFonts($nameFont){
	switch ($nameFont) {
		case 'myfont':
			$font=imageloadfont('fonts/glyphicons-halflings-regular.svg');

			break;
		
		default:
			# code...
			break;
	}
	

	return $font;
}
