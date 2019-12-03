var slideIndex = 1;
var sliderSize = .40;
var myWidth = 0;
var myHeight = 0 ;
var FrameSel = "";

window.onmessage = function(e){
  console.log("onmessage");
  data = e.data.split("|");
  console.log(e.data);
  if (data[0] == 'SelectProject') {
    //console.log('Selector SelectProject = ' + data[1]);
    //var frames= document.getElementsByClassName("project_active");
    //console.log (frames);
   /* for(var i = 0; i < frames.length; ++i){
      console.log("Send GetCode");
    frames[i].contentWindow.postMessage('getCode', '*');;
    }*/
    /*for (var fr in frames) {
      console.log(fr);
    }*/
    looser = "";
    //console.log(data);
    $(".project_active").each(function(){
      //console.log("project_active");
      //console.log($(this).attr("modu-id"));
      if ($(this).attr("modu-id") != data[1]){
        looser = $(this).attr("modu-id");
        //console.log(looser);
        $(".FrameSelected").removeClass("FrameSelected");
      }
    });
    FrameSel = $.escapeSelector("frame-"+data[1]);
    console.log("frame to sel ","#" + FrameSel);
    $("#" + FrameSel).addClass("FrameSelected");
    console.log(data[0] + '|' + data[1] + '|' + looser);
    if (window.top != window.self) {
      window.top.postMessage(data[0] + '|' + data[1] + '|' + looser, '*')
    }

  }
};

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
      //Revisamos si se han agregado menos de 2 sliders y si se han agregado menos de 2
      //los marcamos como project_active para que se active el boton de seleccionar al dar click
      console.log("numeros de Sliders: ",$("#modu_sliders .mySlides").length );
      var ClassActive = "";
      if ($("#modu_sliders .mySlides").length<2)
      {
            ClassActive = "project_active";
      }else{
          ClassActive = "project_inactive";
      }
      var uId=uniqId();
      var newDiv = $(document.createElement('div'));
      newDiv.attr("class","justify-content-center align-items-center text-center my-auto");
      newDiv.css("width",GetWidthScreen());
      newDiv.css("height",GetHeightScreen());
      newDiv.html(
        "<div class=row m-0 text-center><img src='./img/uploading.gif' width='150px' height='150px'></div>"+
        "<div class=row m-0 text-center><spam style='font-size:100px;'>Loading...</spam></div>"
          );
      $("#modu_sliders").append('<div class="thumbnail-container mySlides"><div id="'+uId+'" class="thumbnail"></div></div>');
      $.ajax({
                url: 'ajax/projects.php',
                data: {"cmd" : "CreateProject", "user" : UserParam, "keywords" : KeywordsParams },
                dataType: "json",
                type: 'POST',
                beforeSend: function(){
                  // Show image container
                  $("#"+uId).append(newDiv);
                 },
                 complete:function(data){
                  // Hide image container
                  newDiv.remove();
                 }
            }).done(function( data, textStatus, jqXHR ) {
              $("#"+uId).append('<iframe id="frame-'+ data.codeid +'" class="' + ClassActive +  '" src="http://generator.getmodu.com/generator.php?target=selector&user=' + UserParam + '&codeid=' + data.codeid + '&projectid=' + ProjectIdParam + '" frameborder="0" modu-id="' + data.codeid + '"></iframe>');
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
      });
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

  AddNewProject();
  AddNewProject();
  AddNewProject();
  AddNewProject();
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
 	//console.log("plusSlides(n)");
 	//console.log(n);
 	//console.log("slideIndex=" + slideIndex);
 	if (n<0)
 	{
 		var nPages = $(".bexi_sliders .mySlides").length;

 		
 		//console.log("nPages=" + nPages);

 		if ((slideIndex - nPages) <= 3)
 		{
 			var npos = 1;
 			var slider = 0;
       var uId=uniqId();
       var newDiv = $(document.createElement('div'));
       newDiv.attr("class","justify-content-center align-items-center text-center my-auto");
       newDiv.css("width",GetWidthScreen());
       newDiv.css("height",GetHeightScreen());
       newDiv.html(
       "<div class=row m-0 text-center><img src='./img/uploading.gif' width='150px' height='150px'></div>"+
       "<div class=row m-0 text-center><spam style='font-size:100px;'>Loading...</spam></div>"
         );
         $("#modu_sliders").append('<div class="thumbnail-container mySlides"><div id="'+uId+'" class="thumbnail"></div></div>');
         //<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> 
 			  console.log("cmd", "CreateProject", "user" , UserParam, "keywords" , KeywordsParams);
        $.ajax({
                url: 'ajax/projects.php',
                data: {"cmd" : "CreateProject", "user" : UserParam, "keywords" : KeywordsParams},
                dataType: "json",
                type: 'POST',
                async : true,
                beforeSend: function(){
                  // Show image container
                  $("#"+uId).append(newDiv);
                 },
                 complete:function(data){
                  // Hide image container
                  newDiv.remove();
                 }
            }).done(function( data, textStatus, jqXHR ) {
                $("#"+uId).append('<iframe id="frame-'+ data.codeid +'" src="http://generator.bexi.co/generator.php?target=selector&user=' + UserParam + '&codeid='+ data.codeid + '&projectid=' + ProjectIdParam  +'" frameborder="0" modu-id="' + data.codeid + '"></iframe>');  
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

 				//console.log("pos=" + pos);
 				//console.log("myWidth = " + myWidth);
 				//console.log("left=" + ( pos * sliderSize * myWidth));
         //console.log("nframe - " + (npos-slideIndex));
 				//if ( pos>=0  && pos <= 3)
 				//{
 					$(this).animate({
					    left: pos * sliderSize * myWidth,
					   // opacity: '1'
					 }, 800);
 				//}
        if ((npos-slideIndex)>=1 && (npos-slideIndex)<=2)
        {
           $("iframe", this).addClass("project_active");
           $("iframe", this).removeClass("project_inactive");
        }else{
           $("iframe", this).removeClass("project_active");
           $("iframe", this).addClass("project_inactive");
        }
 				npos = npos + 1;
 			});
 		}
 		
 		slideIndex = slideIndex + 1;
 	}

 	//console.log("n=" + n);
 	if (n>0 && slideIndex > 1)
 	{
 		//console.log("prev");
 		var nPages = $(".bexi_sliders .mySlides").length;
 		
 			/*$(".main_selector").append('<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> <iframe src="http://generator.bexi.co/generator.localhost.php" frameborder="0" onload="this.style.opacity = 1"></iframe></div>');
 			*/
 		var npos = 1;
 		var slider = 0;
 		$(".bexi_sliders .mySlides").each(function() {
 				
 			var pos = npos - slideIndex + 1 ;
 			//console.log("Prev pos==" + pos);
 			//console.log("Prev left=" + (pos * sliderSize * myWidth));
 			$(this).animate({
				left: pos * sliderSize * myWidth,
				//opacity: '1'
			}, 800);

       //console.log("nframe - " + (npos-slideIndex));
       //console.log("left: ", npos-slideIndex);

       /****************************************************
       Activamos los frames que se movera a la pantalla principal el frame -1 que se volvera 0 y el 0 que se volvera 1
       *****************************************************/
       if ((npos-slideIndex)>=-1 && (npos-slideIndex)<=0)
        {
           //console.log("project_active : ", npos-slideIndex);
           $("iframe",this).addClass("project_active");
           $("iframe", this).removeClass("project_inactive");
        }else{
          //console.log("project_inactive : ", npos-slideIndex);
          $("iframe",this).removeClass("project_active");
          $("iframe", this).addClass("project_inactive");
        }

 			npos = npos + 1;
 		});
 		slideIndex = slideIndex - 1;
 	}
 }





