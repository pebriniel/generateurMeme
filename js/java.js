function geneImage(idImg,sizeTop,sizeBot,clrTop,clrBot,textTop,textBot){
	/*console.log($image,$type,$sizeTop,$sizeBot,$clrTop,$clrBot,$textTop,textBot);*/
	$.get('include/fonction.php',{


		sizeTop:sizeTop,
		sizeBot:sizeBot,
		/*clrTop:clrTop,
		clrBot:clrBot,*/
		textTop:textTop,
		textBot:textBot,
		idImg:idImg,


	})
    .done(function(data) {

    })
    .fail(function(data) {
        alert('Error: ' + data);
    });


}
