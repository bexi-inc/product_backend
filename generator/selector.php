<!DOCTYPE html>
<html>
<head>
	<script src="includes/jquery-3.4.1.min.js"></script>
	<style type="text/css">
			/* Variables */
	:root {
	/*--thumbnail-width : 1440px;*/
	--thumbnail-height : 280vh;
	--thumbnail-zoom: 0.35;
	}

	/* Basic CSS Reset */
	* {
	  margin: 0;
	  padding: 0;
	  box-sizing: border-box;
	}

	/* Demo-specific styling */
	body {
	  text-align: center;
	  /*margin-top: 50px;*/
	}

	/* This container helps the thumbnail behave as if it were an unscaled IMG element */
	.thumbnail-container {
	  /*width: calc(var(--thumbnail-width) * var(--thumbnail-zoom));*/
	 /*width: 36%;*/
	  /*height: calc(var(--thumbnail-height) * var(--thumbnail-zoom));*/
	  height: 100%;
	  display: inline-block;
	  overflow: hidden;
	  position: absolute;
	  top: 0;
	  background: #f9f9f9;
	  float: left;
	  /*border-style: solid;*/	
	}

	/* Image Icon for the Background 
	.thumbnail-container::before {
	  position: absolute;
	  left: calc(50% - 16px);
	  top: calc(50% - 18px);
	  opacity: 0.2;
	  display: block;
	  -ms-zoom: 2;
	  -o-transform: scale(2);
	  -moz-transform: scale(2);
	  -webkit-transform: scale(2);
	  content: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDMyIDMyIiBoZWlnaHQ9IjMycHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAzMiAzMiIgd2lkdGg9IjMycHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnIGlkPSJwaG90b18xXyI+PHBhdGggZD0iTTI3LDBINUMyLjc5MSwwLDEsMS43OTEsMSw0djI0YzAsMi4yMDksMS43OTEsNCw0LDRoMjJjMi4yMDksMCw0LTEuNzkxLDQtNFY0QzMxLDEuNzkxLDI5LjIwOSwwLDI3LDB6ICAgIE0yOSwyOGMwLDEuMTAyLTAuODk4LDItMiwySDVjLTEuMTAzLDAtMi0wLjg5OC0yLTJWNGMwLTEuMTAzLDAuODk3LTIsMi0yaDIyYzEuMTAyLDAsMiwwLjg5NywyLDJWMjh6IiBmaWxsPSIjMzMzMzMzIi8+PHBhdGggZD0iTTI2LDRINkM1LjQ0Nyw0LDUsNC40NDcsNSw1djE4YzAsMC41NTMsMC40NDcsMSwxLDFoMjBjMC41NTMsMCwxLTAuNDQ3LDEtMVY1QzI3LDQuNDQ3LDI2LjU1Myw0LDI2LDR6ICAgIE0yNiw1djEzLjg2OWwtMy4yNS0zLjUzQzIyLjU1OSwxNS4xMjMsMjIuMjg3LDE1LDIyLDE1cy0wLjU2MSwwLjEyMy0wLjc1LDAuMzM5bC0yLjYwNCwyLjk1bC03Ljg5Ni04Ljk1ICAgQzEwLjU2LDkuMTIzLDEwLjI4Nyw5LDEwLDlTOS40NCw5LjEyMyw5LjI1LDkuMzM5TDYsMTMuMDg3VjVIMjZ6IE02LDE0LjZsNC00LjZsOC4wNjYsOS4xNDNsMC41OCwwLjY1OEwyMS40MDgsMjNINlYxNC42eiAgICBNMjIuNzQsMjNsLTMuNDI4LTMuOTU1TDIyLDE2bDQsNC4zNzlWMjNIMjIuNzR6IiBmaWxsPSIjMzMzMzMzIi8+PHBhdGggZD0iTTIwLDEzYzEuNjU2LDAsMy0xLjM0MywzLTNzLTEuMzQ0LTMtMy0zYy0xLjY1OCwwLTMsMS4zNDMtMywzUzE4LjM0MiwxMywyMCwxM3ogTTIwLDhjMS4xMDIsMCwyLDAuODk3LDIsMiAgIHMtMC44OTgsMi0yLDJjLTEuMTA0LDAtMi0wLjg5Ny0yLTJTMTguODk2LDgsMjAsOHoiIGZpbGw9IiMzMzMzMzMiLz48L2c+PC9zdmc+");
	}*/

	/* This is a masking container for the zoomed iframe element */
	.thumbnail {
	  -ms-zoom: var(--thumbnail-zoom);
	  -moz-transform: scale(var(--thumbnail-zoom));
	  -moz-transform-origin: 0 0;
	  -o-transform: scale(var(--thumbnail-zoom));
	  -o-transform-origin: 0 0;
	  -webkit-transform: scale(var(--thumbnail-zoom));
	  -webkit-transform-origin: 0 0;
	}

	/* This is our screen sizing */
	.thumbnail, .thumbnail iframe {
	  width: var(--thumbnail-width);
	  height: var(--thumbnail-height);
	}

	/* This facilitates the fade-in transition instead of flicker. It also helps us maintain the illusion that this is an image, since some webpages will have a preloading animation or wait for some images to download */
	.thumbnail iframe {
	  opacity: 0;
	  transition: all 300ms ease-in-out;
	  border-radius:25px;
	  -webkit-box-shadow:0 5px 20px rgba(81,91,104,.2),0 -5px 10px rgba(81,91,104,.2);
	  box-shadow:0 5px 20px rgba(81,91,104,.2),0 -5px 10px rgba(81,91,104,.2);
	}

	/* This pseudo element masks the iframe, so that mouse wheel scrolling and clicking do not affect the simulated "screenshot" */
	/*.thumbnail:after {
	  content: "";
	  display: block;
	  position: absolute;
	  top: 0;
	  left: 0;
	  right: 10px;
	  bottom: 0;
	}*/

	.main_selector
	{
		width: 100%;
		height: 100%;
		position: absolute;
	  	margin: auto;

	}

	/* Next & previous buttons */
	.selector_prev, .selector_next {
	  cursor: pointer;
	  position: absolute;
	  top: 50%;
	  width: auto;
	  margin-top: -22px;
	  padding: 16px;
	  color: white;
	  font-weight: bold;
	  font-size: 18px;
	  transition: 0.6s ease;
	  border-radius: 0 3px 3px 0;
	  user-select: none;
	  z-index: 999;
	  background-color: rgba(0,0,0,0.3);
	}

	/* Position the "next button" to the right */
	.selector_next {
	  right: 0;
	  border-radius: 3px 0 0 3px;
	}

	.selector_prev {
	  left: 0;
	  border-radius: 3px 0 0 3px;
	}

	/* On hover, add a black background color with a little bit see-through */
	.selector_prev:hover, .selector_next:hover {
	  background-color: rgba(0,0,0,0.7);
	}

/*
	.mySlides:hover:after{
		  content: url('imgs/edit.png');;
		  width: 100%;
		  height: 100%;
		  position: absolute;
		  left: 0;
		  top: 0;
		  padding-top: 50%;
		  background-color:rgba(0, 0, 0, 0.3);
	}*/

	</style>
	<script type="text/javascript">
		var Widthparam = <? echo (isset($_REQUEST["screen_width"]) ? $_REQUEST["screen_width"] : "0"); ?>;
		var Heightparam = <? echo (isset($_REQUEST["screen_height"]) ? $_REQUEST["screen_height"] : "0"); ?>;
		var UserParam = <? echo (isset($_REQUEST["user"]) ? $_REQUEST["user"] : "0"); ?>;

		function SelectProject(ProjectId)
		{
			alert("this works!! " + ProjectId);
		}


		window.onmessage = function(e){
			data = e.data.split("|");
		    if (data[0] == 'SelectProject') {
		        console.log('Selector SelectProject = ' + data[1]);
		         if (window.top != window.self) {
		         	window.top.postMessage(data[0] + '|' + data[1], '*')
		         }

		    }
		};

	</script>
	<script type="text/javascript" src="includes/bexi_selector.js"></script>
</head>
<body>
<div class="main_selector">
	<!-- Next and previous buttons -->
	<a class="selector_prev" onclick="plusSlides(1)">&#10094;</a>
	<a class="selector_next" onclick="plusSlides(-1)">&#10095;</a>
	<div class="bexi_sliders" style="width: 90%; margin-left: 5%; position: relative; height: 100%; margin-right: 5%; overflow: hidden;" >
		<div class="thumbnail-container mySlides">
			<div class="thumbnail ">
			  <iframe src="http://generator.bexi.co/generator.php?target=selector&user=<? echo $_REQUEST["user"] ?>" frameborder="0" onload="this.style.opacity = 1" ></iframe>
			</div>
		</div>

		<div class="thumbnail-container mySlides">
			<div class="thumbnail">
			  <iframe src="http://generator.bexi.co/generator.php?target=selector&user=<? echo $_REQUEST["user"] ?>" frameborder="0" onload="this.style.opacity = 1"></iframe>
			</div>
		</div>

		<div class="thumbnail-container mySlides">
			<div class="thumbnail">
			  <iframe src="http://generator.bexi.co/generator.php?target=selector&user=<? echo $_REQUEST["user"] ?>" frameborder="0" onload="this.style.opacity = 1"></iframe>
			</div>
		</div>

		<div class="thumbnail-container mySlides">
			<div class="thumbnail">
			  <iframe src="http://generator.bexi.co/generator.php?target=selector&user=<? echo $_REQUEST["user"] ?>" frameborder="0" onload="this.style.opacity = 1"></iframe>
			</div>
		</div>
	</div>
</div>
</body>
</html>
