function geneImage(idImg,sizeTop=null,sizeBot=null,clrTop=null,clrBot=null,textTop,textBot){
	/*console.log($image,$type,$sizeTop,$sizeBot,$clrTop,$clrBot,$textTop,textBot);*/
	$.get('include/fonction.php',{


		sizeTop:sizeTop,
		sizeBot:sizeBot,
		clrTop:clrTop,
		clrBot:clrBot,
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
