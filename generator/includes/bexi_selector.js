var slideIndex = 1;
var sliderSize = .40;
var myWidth = 0;
var myHeight = 0 ;

function GetWidthScreen()
{
  myWidth = 0;
  if (window !== top) {
  	myWidth = window.innerWidth ;
  }else{
  	myWidth = $(window).width();
  }

  if (myWidth==0)
  {
  	myWidth = window.screen.width;
  }

  if (myWidth==0)
  {
  		myWidth = Widthparam;
  }
  console.log("Widthparam");
  console.log(Widthparam);
  console.log("myWidth ");
  console.log(myWidth);
  return myWidth;
};

function GetHeightScreen()
{
  myHeight = 0;
  if (window !== top) {
    myHeight = window.innerHeight ;
  }else{
    myHeight = $(window).height();
  }

  if (myWidth==0)
  {
    myHeight = window.screen.height;
  }

  if (myHeight==0)
  {
      myHeight = Heightparam;
  }
  console.log("Heightparam");
  console.log(Heightparam);
  console.log("myHeight ");
  console.log(myHeight);
  return myHeight;
};

 $(function() {
//
 	//Set Variables 
 	document.documentElement.style.setProperty('--thumbnail-width', GetWidthScreen()+"px");
 	document.documentElement.style.setProperty('--thumbnail-height', (GetHeightScreen() * 2.5) + "px");
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
     $(".bexi_sliders .mySlides").each(function() {
     	if (n<=4)
     	{
     		console.log("slider");
     		var lpos =(n-1) * sliderSize * GetWidthScreen();
     		console.log("left:" + lpos);
     		$(this).css({ 'top': 0, 'left': lpos });
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
 		var nPages = $(".bexi_sliders .mySlides").length;

 		
 		console.log("nPages=" + nPages);

 		if ((slideIndex - nPages) <= 3)
 		{
 			var npos = 1;
 			var slider = 0;
 			$(".bexi_sliders").append('<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> <iframe src="http://generator.bexi.co/generator.localhost.php?target=selector" frameborder="0" onload="this.style.opacity = 1"></iframe></div>');
 			$(".bexi_sliders .mySlides").each(function() {
 				/*if (npos == slideIndex)
 				{
 					 $(this).animate({
					    left: '-9999px',
					    opacity: '0.5'
					  }, 800);
 				}*/

 				//console.log("npos=" + npos);

 				var pos = npos - slideIndex - 1;

 				console.log("pos=" + pos);
 				console.log("myWidth = " + myWidth);
 				console.log("left=" + ( pos * sliderSize * myWidth));
 				//if ( pos>=0  && pos <= 3)
 				//{
 					$(this).animate({
					    left: pos * sliderSize * myWidth,
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
 		var nPages = $(".bexi_sliders .mySlides").length;
 		
 			/*$(".main_selector").append('<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> <iframe src="http://generator.bexi.co/generator.localhost.php" frameborder="0" onload="this.style.opacity = 1"></iframe></div>');
 			*/
 		var npos = 1;
 		var slider = 0;
 		$(".bexi_sliders .mySlides").each(function() {
 				
 			var pos = npos - slideIndex + 1 ;
 			console.log("Prev pos==" + pos);
 			console.log("Prev left=" + (pos * sliderSize * myWidth));
 			$(this).animate({
				left: pos * sliderSize * myWidth,
				opacity: '1'
			}, 800);

 			npos = npos + 1;
 		});
 		slideIndex = slideIndex - 1;
 	}
 }

