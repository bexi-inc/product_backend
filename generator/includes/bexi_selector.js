var slideIndex = 1;
var sliderSize = .40;
var myWidth = 0;
var myHeight = 0 ;

function uniqId() {
  return Math.round(new Date().getTime() + (Math.random() * 100));
}


function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};


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


function AddNewProject()
    {
      $.ajax({
                url: 'ajax/projects.php',
                data: {"cmd" : "CreateProject", "user" : "<? echo $_REQUEST['user']; ?>"},
                dataType: "json",
                type: 'POST',
            }).done(function( data, textStatus, jqXHR ) {
              $("#modu_sliders").append('<div class="thumbnail-container mySlides"><div class="thumbnail"><iframe src="http://generator.getmodu.com/generator.php?target=selector&user=' + UserParam + '&codeid=' + data.codeid + '" frameborder="0" onload="this.style.opacity = 1" modu-id="' + data.codeid + '"></iframe></div></div>');
              console.log($(".bexi_sliders .mySlides").length);
               if ($(".bexi_sliders .mySlides").length<=4)
               {
                 var n=1;
                 $(".bexi_sliders .mySlides").each(function() {
                   if (n<=4)
                   {
                     console.log("sliders");
                     var lpos =(n-1) * sliderSize * GetWidthScreen();
                     console.log("left:" + lpos);
                     $(this).css({ 'top': 0, 'left': lpos });
                   }
                     n = n +1;
                 });
              }
           if ( console && console.log ) {
             console.log("data");
             console.log(data);
               console.log( "La solicitud se ha completado correctamente." );
           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);
           }
      });;
    }

 $(function() {
//Set Variables 
   document.documentElement.style.setProperty('--thumbnail-width', GetWidthScreen()+"px");
   //document.documentElement.style.setProperty('--thumbnail-height', (GetHeightScreen() * 2.5) + "px");
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

$( document ).ready(function() {
    console.log("ready");
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

 			
        $.ajax({
                url: 'ajax/projects.php',
                data: {"cmd" : "CreateProject", "user" : UserParam},
                dataType: "json",
                type: 'POST',
                async : false,
            }).done(function( data, textStatus, jqXHR ) {
                $(".bexi_sliders").append('<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> <iframe src="http://generator.bexi.co/generator.php?target=selector&user=' + UserParam + '&codeid='+ data.codeid +'" frameborder="0" onload="this.style.opacity = 1" modu-id="' + data.codeid + '"></iframe></div>');  
             })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);
           }
      });
    
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
         console.log("nframe - " + (npos-slideIndex));
 				//if ( pos>=0  && pos <= 3)
 				//{
 					$(this).animate({
					    left: pos * sliderSize * myWidth,
					    opacity: '1'
					 }, 800);
 				//}
        if ((npos-slideIndex)>=1 && (npos-slideIndex)<=2)
        {
           $("iframe", this).addClass("project_active");
        }else{
           $("iframe", this).removeClass("project_active");
        }
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

       console.log("nframe - " + (npos-slideIndex));
       if ((npos-slideIndex)>=1 && (npos-slideIndex)<=2)
        {
           $("iframe",this).addClass("project_active");
        }else{
           $("iframe",this).removeClass("project_active");
        }

 			npos = npos + 1;
 		});
 		slideIndex = slideIndex - 1;
 	}
 }





