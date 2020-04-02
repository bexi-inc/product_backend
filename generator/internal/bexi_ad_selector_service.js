var slideIndex = 1;
var sliderSize = .40;
var myWidth = 0;
var myHeight = 0 ;
var FrameSel = "";

window.onmessage = function(e){
  $(".select").removeClass("hidden");
  data = e.data.split("|");
  if (data[0] == 'SelectProject') {
    looser = "";
    $(".project_active").each(function(){
      if ($(this).attr("modu-id") != data[1]){
        looser = $(this).attr("modu-id");
        $(".FrameSelected").removeClass("FrameSelected");
      }
    });
    $("#" + data[2]).addClass("FrameSelected");
    if (window.top != window.self) {
      window.top.postMessage(data[0] + '|' + data[1] + '|' + looser, '*')
    }

  }
};

function uniqId() {
  return Math.round(new Date().getTime() + (Math.random() * 100));
}


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
  return myHeight;
};

function frameload(ID){
  $("#load-"+ID).remove();
 }

 $(window).on('resize', function(){
  document.documentElement.style.setProperty('--zoom-factor', GetWidthScreen()/2742.8571);
  var size=$(".thumbnail-container").width()+20;
  $("#pre-thumbnail").css('-webkit-transform',"translateX(-"+(slideIndex-1)*size+"px)");
  $("#pre-thumbnail").css('-moz-transform',"translateX(-"+(slideIndex-1)*size+"px)");
  $("#pre-thumbnail").css('transform',"translateX(-"+(slideIndex-1)*size+"px)");
  $("#pre-thumbnail").css("top",(slideIndex-1)*size+"px");
});

function select_edit(){
  var id=$("#pre-thumbnail").find(".FrameSelected").find("iframe").attr("modu-id");
  window.location.href = 'http://internal.bexi.ai/adgenerator-service.php?'+"cmd=editor&user=-100&codename=0&create=true&codeid="+id;
}

function AddNewProject()
    {
      //Revisamos si se han agregado menos de 2 sliders y si se han agregado menos de 2
      //los marcamos como project_active para que se active el boton de seleccionar al dar click
      var ClassActive = "";
      if ($("#pre-thumbnail .mySlides").length<2)
      {
            ClassActive = "project_active";
      }else{
          ClassActive = "project_inactive";
      }
      var uId=uniqId();
      var newDiv = $(document.createElement('div'));
      newDiv.attr("class","justify-content-center align-items-center text-center");
      newDiv.attr("id","load-"+uId);
      newDiv.css("top","calc( 50% - (192px/2))");
      newDiv.css("left","0");
      newDiv.css("right","0");
      newDiv.css("position","absolute");
      newDiv.css("z-index","100");
      newDiv.html(
        '<div class="Spinner"><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div></div>'
        );
        $("#pre-thumbnail").append('<div class="thumbnail-container mySlides"><div id="'+uId+'" class="thumbnail pointer_SelectProject"></div></div>');
        $("#"+uId).append(newDiv);
      $.ajax({
                url: 'adgenerator-service.php',
                data: {"cmd" : "CreateAd", "user" : UserParam, "keywords" : KeywordsParams,"headline": Headline, "cta":Cta},
                dataType: "json",
                type: 'POST',
                beforeSend: function(){
                  // Show image container
                 },
                 complete:function(data){
                  // Hide image container
                  //newDiv.remove();
                 }
            }).done(function( data, textStatus, jqXHR ) {
              $("#"+uId).append('<iframe onload="frameload('+uId+')" id="frame-'+ data.codeid +'" class="' + ClassActive +  '" src="http://internal.bexi.ai/adgenerator-service.php?cmd=selector&user=' + UserParam + '&codeid=' + data.codeid + '" frameborder="0" modu-id="' + data.codeid + '"></iframe>');
              $("#"+uId).attr("bexi-code",data.codeid);
              $("#"+uId).click(
                function(){
                 window.parent.postMessage('SelectProject|' + $(this).attr("bexi-code")+"|"+uId, '*');
               }
               );
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);
           }
      });
    }

 $(function() {
//Set Variables 
   document.documentElement.style.setProperty('--zoom-factor', GetWidthScreen()/2742.8571);
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

  /*
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
*/
});


 function plusSlides(n)
 {
 	//console.log("plusSlides(n)");
 	//console.log(n);
 	//console.log("slideIndex=" + slideIndex);
 	if (n<0)
 	{
 		var nPages = $(".pre-thumbnail .mySlides").length;

 		
 		//console.log("nPages=" + nPages);

 		if ((slideIndex - nPages) <= 3)
 		{
      $(".selector_prev").removeClass("hidden");
      $(".selector_prev").addClass("visible");
 			var npos = 1;
 			var slider = 0;
       var uId=uniqId();
       var newDiv = $(document.createElement('div'));
       newDiv.attr("class","justify-content-center align-items-center text-center");
       newDiv.attr("id","load-"+uId);
       newDiv.css("top","calc( 50% - (192px/2))");
       newDiv.css("left","0");
       newDiv.css("right","0");
       newDiv.css("position","absolute");
       newDiv.css("z-index","100");
       newDiv.html(
         '<div class="Spinner"><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div><div class="Spinner-inner"></div></div>'
         );
         $("#pre-thumbnail").append('<div class="thumbnail-container mySlides"><div id="'+uId+'" class="thumbnail pointer_SelectProject"></div></div>');

         //<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> 
        $.ajax({
                url: 'adgenerator-service.php',
                data: {"cmd" : "CreateAd", "user" : UserParam, "keywords" : KeywordsParams,"headline": Headline, "cta":Cta},
                dataType: "json",
                type: 'POST',
                async : true,
                beforeSend: function(){

                 },
                 complete:function(data){
                  // Hide image container
                  //newDiv.remove();
                 }
            }).done(function( data, textStatus, jqXHR ) {
                // Show image container
                $("#"+uId).append(newDiv);
                $("#"+uId).append('<iframe onload="frameload('+uId+')" id="frame-'+ data.codeid +'" src="http://internal.bexi.ai/adgenerator-service.php?cmd=selector&user=' + UserParam + '&codeid=' + data.codeid + '" frameborder="0" modu-id="' + data.codeid + '"></iframe>');
                $("#"+uId).attr("bexi-code",data.codeid);
                $("#"+uId).click(
                  function(){
                    window.parent.postMessage('SelectProject|' + $(this).attr("bexi-code")+"|"+uId, '*');
                 }
                 );
              })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);
           }
      });

      var size=$(".thumbnail-container").width()+20;
      $("#pre-thumbnail").animate({top: slideIndex*size},{
        step: function(now,fx) {
          $(this).css('-webkit-transform',"translateX(-"+(now)+"px)");
          $(this).css('-moz-transform',"translateX(-"+(now)+"px)");
          $(this).css('transform',"translateX(-"+(now)+"px)");
        },
        duration:'slow'
      },'linear');
 			$(".pre-thumbnail .mySlides").each(function() {
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
/*
 					$(this).animate({
					    left: pos * sliderSize * myWidth,
					   // opacity: '1'
           }, 800);
*/
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
 		var nPages = $(".pre-thumbnail .mySlides").length;
 		
 			/*$(".main_selector").append('<div class="thumbnail-container mySlides" style="top:0px; left: 9999px"><div class="thumbnail"> <iframe src="http://generator.bexi.co/generator.localhost.php" frameborder="0" onload="this.style.opacity = 1"></iframe></div>');
 			*/
 		var npos = 1;
     var slider = 0;
     var size=$(".thumbnail-container").width()+20;
     var position=parseFloat($('#pre-thumbnail').css('top').replace('px',''));
     var final=position-size;
     $("#pre-thumbnail").animate({top: final},{
       step: function(now,fx) {
         $(this).css('-webkit-transform',"translateX(-"+(now)+"px)");
         $(this).css('-moz-transform',"translateX(-"+(now)+"px)");
         $(this).css('transform',"translateX(-"+(now)+"px)");
       },
       duration:'slow'
     },'linear');
 		$(".pre-thumbnail .mySlides").each(function() {
 				
 			var pos = npos - slideIndex + 1 ;
 			//console.log("Prev pos==" + pos);
 			//console.log("Prev left=" + (pos * sliderSize * myWidth));
/* 
       $(this).animate({
				left: pos * sliderSize * myWidth,
				//opacity: '1'
			}, 800);
*/
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
   if(slideIndex===1)
   {
     $(".selector_prev").addClass("hidden");
     $(".selector_prev").removeClass("visible");
   }
 }





