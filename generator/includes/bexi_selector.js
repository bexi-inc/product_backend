var slideIndex = 1;
var sliderSize = .40;

function GetWidthScreen()
{
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  console.log ("myWidth "+myWidth);
  return myWidth;
};

 $(function() {
//
 	//Set Variables 
 	document.documentElement.style.setProperty('--thumbnail-width', GetWidthScreen()+"px");
 	//document.documentElement.style.setProperty('--thumbnail-height', $( window ).height() * 4);
/*function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  //var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      //slides[i].style.display = "none";
  }
 /* for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  //dots[slideIndex-1].className += " active";
} */
	console.log("slider");

	var n=1;
     $(".main_selector .mySlides").each(function() {
     	if (n<=4)
     	{
     		console.log("slider");
     		var lpos =(n-1) * sliderSize * GetWidthScreen();
     		$(this).offset({ top: 0, left: lpos });
     	}
       	n = n +1;
     });


});


 function plusSlides(n)
 {
 	console.log("plusSlides(n)");
 	console.log(n);
 	console.log("slideIndex=" + slideIndex);
 	if (n<0)
 	{
 		var nPages = $(".main_selector .mySlides").length;

 		
 		console.log("nPages=" + nPages);

 		if ((slideIndex - nPages) <= 3)
 		{
 			var npos = 1;
 			var slider = 0;
 			$(".main_selector").append('<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> <iframe src="http://generator.bexi.co/generator.localhost.php" frameborder="0" onload="this.style.opacity = 1"></iframe></div>');
 			$(".main_selector .mySlides").each(function() {
 				/*if (npos == slideIndex)
 				{
 					 $(this).animate({
					    left: '-9999px',
					    opacity: '0.5'
					  }, 800);
 				}*/

 				//console.log("npos=" + npos);

 				var pos = npos - slideIndex - 1;

 				//console.log("pos=" + pos);

 				//console.log("left=" + (pos * sliderSize * $( window ).width()));
 				//if ( pos>=0  && pos <= 3)
 				//{
 					$(this).animate({
					    left: pos * sliderSize * $( window ).width(),
					    opacity: '1'
					 }, 800);
 				//}
 				npos = npos + 1;
 			});
 		}
 		
 		slideIndex = slideIndex + 1;
 	}

 	console.log("n=" + n);
 	if (n>0 && slideIndex > 1)
 	{
 		console.log("prev");
 		var nPages = $(".main_selector .mySlides").length;
 		
 			/*$(".main_selector").append('<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> <iframe src="http://generator.bexi.co/generator.localhost.php" frameborder="0" onload="this.style.opacity = 1"></iframe></div>');
 			*/
 		var npos = 1;
 		var slider = 0;
 		$(".main_selector .mySlides").each(function() {
 				
 			var pos = npos - slideIndex + 1 ;
 			console.log("Prev pos==" + pos);
 			console.log("Prev left=" + (pos * sliderSize * $( window ).width()));
 			$(this).animate({
				left: pos * sliderSize * $( window ).width(),
				opacity: '1'
			}, 800);

 			npos = npos + 1;
 		});
 		slideIndex = slideIndex - 1;
 	}
 }

