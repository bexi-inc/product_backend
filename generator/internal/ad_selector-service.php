<?
include "/var/www/generator.bexi.co/public_html/product_backend/generator/includes/global.php";
include "/var/www/generator.bexi.co/public_html/product_backend/generator/includes/utils.php";
include "/var/www/generator.bexi.co/public_html/product_backend/generator/config.php";
include "/var/www/generator.bexi.co/public_html/product_backend/generator/vendor/autoload.php";

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;


$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => $AWS_REGION,
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

Crew\Unsplash\HttpClient::init([
    'applicationId' => $unspash_appid,
    'secret'        => $unspash_secret,
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);


?><!DOCTYPE html>
<html>
<head>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<style type="text/css">
			/* Variables */

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

	:root {
        --width: 1210px;
        --height: 644px;
        /* scale **/
        --zoom-factor: 0.7;
      }
      .thumbnail iframe {
        width: var(--width);
        height: var(--height);
      }
      .thumbnail {
        position: relative;
        display: table-caption;
		width: var(--width);
        height: var(--height);
        -ms-zoom: var(--zoom-factor);
        -moz-transform: scale(var(--zoom-factor));
        -moz-transform-origin: 0 0;
        -o-transform: scale(var(--zoom-factor));
        -o-transform-origin: 0 0;
        -webkit-transform: scale(var(--zoom-factor));
        -webkit-transform-origin: 0 0;
      }
      /** no interaction **/
      .thumbnail:after {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
      }
      .thumbnail-container {
        width: calc(var(--width) * var(--zoom-factor));
        height: calc(var(--height) * var(--zoom-factor));
        display: inline-block;
        position: relative;
        margin-right: 20px;
      }
      .bexi_sliders {
        display: flex;
        flex-flow: row wrap;
        align-items: center;
        height: 100vh;
		overflow:hidden;
      }

	  .pre-thumbnail{
		  white-space:nowrap;
		  padding-left:20px;
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
	}/*	  background-color: rgba(0,0,0,0.3); */

	/* Position the "next button" to the right */
	.selector_next {
	  margin-right:15px;
	  right: 0;
	  border-radius: 3px 0 0 3px;
	  background-image: url("http://generator.bexi.ai/imgs/right_gray.png");
	  background-position: center; /* Center the image */
	  background-repeat: no-repeat; /* Do not repeat the image */
	  background-size: cover; /* Resize the background image to cover the entire container */
	}

	.selector_prev {
	  margin-left:15px;
	  left: 0;
	  border-radius: 3px 0 0 3px;
	  background-image: url("http://generator.bexi.ai/imgs/left_gray.png");
	  background-position: center; /* Center the image */
	  background-repeat: no-repeat; /* Do not repeat the image */
	  background-size: cover; /* Resize the background image to cover the entire container */
	}

	/* On hover, add a black background color with a little bit see-through */
	.selector_prev:hover{
		background-image: url("http://generator.bexi.ai/generator/imgs/left.png");
	}/*background-color: rgba(0,0,0,0.7); */

	.selector_next:hover {
		background-image: url("http://generator.bexi.ai/imgs/right.png");
	}/*background-color: rgba(0,0,0,0.7); */
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


	.FrameSelected {
	  -webkit-box-shadow:0 5px 20px rgba(45, 211, 214,.2),0 -5px 10px rgba(45, 211, 214,.2) !important;
	  box-shadow:0 15px 30px rgba(45, 211, 214,.9),0 5px 20px rgba(45, 211, 214,.9) !important;
	}

	.pointer_SelectProject
	{
		cursor:pointer;
	}

	.project_inactive
	{
		opacity : 0.2;
	}

	.visible{
		visibility: visible;
	}

	.hidden{
		visibility: hidden;
	}

	.Spinner{position:relative;display:block;width:1em;height:1em;margin:0 auto;font-size:192px}.Spinner-inner{position:absolute;width:.078125em;height:.078125em;border-radius:50%;background-color:#2dd3d6;-webkit-animation:Spinner-animation 1.2s linear infinite;-moz-animation:Spinner-animation 1.2s linear infinite;-o-animation:Spinner-animation 1.2s linear infinite;-ms-animation:Spinner-animation 1.2s linear infinite;animation:Spinner-animation 1.2s linear infinite}.Spinner-inner:first-child{top:.453125em;left:.828125em;-webkit-animation-delay:0;-moz-animation-delay:0;-o-animation-delay:0;-ms-animation-delay:0;animation-delay:0}.Spinner-inner:nth-child(2){top:.28125em;left:.78125em;-webkit-animation-delay:-.1s;-moz-animation-delay:-.1s;-o-animation-delay:-.1s;-ms-animation-delay:-.1s;animation-delay:-.1s}.Spinner-inner:nth-child(3){top:.140625em;left:.640625em;-webkit-animation-delay:-.2s;-moz-animation-delay:-.2s;-o-animation-delay:-.2s;-ms-animation-delay:-.2s;animation-delay:-.2s}.Spinner-inner:nth-child(4){top:.09375em;left:.453125em;-webkit-animation-delay:-.3s;-moz-animation-delay:-.3s;-o-animation-delay:-.3s;-ms-animation-delay:-.3s;animation-delay:-.3s}.Spinner-inner:nth-child(5){top:.140625em;left:.28125em;-webkit-animation-delay:-.4s;-moz-animation-delay:-.4s;-o-animation-delay:-.4s;-ms-animation-delay:-.4s;animation-delay:-.4s}.Spinner-inner:nth-child(6){top:.28125em;left:.140625em;-webkit-animation-delay:-.5s;-moz-animation-delay:-.5s;-o-animation-delay:-.5s;-ms-animation-delay:-.5s;animation-delay:-.5s}.Spinner-inner:nth-child(7){top:.453125em;left:.09375em;-webkit-animation-delay:-.6s;-moz-animation-delay:-.6s;-o-animation-delay:-.6s;-ms-animation-delay:-.6s;animation-delay:-.6s}.Spinner-inner:nth-child(8){top:.640625em;left:.140625em;-webkit-animation-delay:-.7s;-moz-animation-delay:-.7s;-o-animation-delay:-.7s;-ms-animation-delay:-.7s;animation-delay:-.7s}.Spinner-inner:nth-child(9){top:.78125em;left:.28125em;-webkit-animation-delay:-.8s;-moz-animation-delay:-.8s;-o-animation-delay:-.8s;-ms-animation-delay:-.8s;animation-delay:-.8s}.Spinner-inner:nth-child(10){top:.828125em;left:.453125em;-webkit-animation-delay:-.9s;-moz-animation-delay:-.9s;-o-animation-delay:-.9s;-ms-animation-delay:-.9s;animation-delay:-.9s}.Spinner-inner:nth-child(11){top:.78125em;left:.640625em;-webkit-animation-delay:-1s;-moz-animation-delay:-1s;-o-animation-delay:-1s;-ms-animation-delay:-1s;animation-delay:-1s}.Spinner-inner:nth-child(12){top:.640625em;left:.78125em;-webkit-animation-delay:-1.1s;-moz-animation-delay:-1.1s;-o-animation-delay:-1.1s;-ms-animation-delay:-1.1s;animation-delay:-1.1s}@-moz-keyframes Spinner-animation{0%,20%,80%,to{-webkit-transform:scale(1);-moz-transform:scale(1);-o-transform:scale(1);-ms-transform:scale(1);transform:scale(1);opacity:0;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";filter:alpha(opacity=0)}50%{-webkit-transform:scale(1.5);-moz-transform:scale(1.5);-o-transform:scale(1.5);-ms-transform:scale(1.5);transform:scale(1.5);opacity:1;-ms-filter:none;filter:none}}@-webkit-keyframes Spinner-animation{0%,20%,80%,to{-webkit-transform:scale(1);-moz-transform:scale(1);-o-transform:scale(1);-ms-transform:scale(1);transform:scale(1);opacity:0;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";filter:alpha(opacity=0)}50%{-webkit-transform:scale(1.5);-moz-transform:scale(1.5);-o-transform:scale(1.5);-ms-transform:scale(1.5);transform:scale(1.5);opacity:1;-ms-filter:none;filter:none}}@-o-keyframes Spinner-animation{0%,20%,80%,to{-webkit-transform:scale(1);-moz-transform:scale(1);-o-transform:scale(1);-ms-transform:scale(1);transform:scale(1);opacity:0;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";filter:alpha(opacity=0)}50%{-webkit-transform:scale(1.5);-moz-transform:scale(1.5);-o-transform:scale(1.5);-ms-transform:scale(1.5);transform:scale(1.5);opacity:1;-ms-filter:none;filter:none}}@keyframes Spinner-animation{0%,20%,80%,to{-webkit-transform:scale(1);-moz-transform:scale(1);-o-transform:scale(1);-ms-transform:scale(1);transform:scale(1);opacity:0;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";filter:alpha(opacity=0)}50%{-webkit-transform:scale(1.5);-moz-transform:scale(1.5);-o-transform:scale(1.5);-ms-transform:scale(1.5);transform:scale(1.5);opacity:1;-ms-filter:none;filter:none}}
	</style>
	<script type="text/javascript">
		var Widthparam = <? echo (isset($_REQUEST["screen_width"]) ? $_REQUEST["screen_width"] : "0"); ?>;
		var Heightparam = <? echo (isset($_REQUEST["screen_height"]) ? $_REQUEST["screen_height"] : "0"); ?>;
        var UserParam = '-100';
        var Headline = '<? echo (isset($_REQUEST["headline"]) ? $_REQUEST["headline"] : ""); ?>';
        var Cta = '<? echo (isset($_REQUEST["cta"]) ? $_REQUEST["cta"] : ""); ?>';
		var Codename = '<? echo (isset($_REQUEST["codename"]) ? $_REQUEST["codename"] : "0"); ?>';
		var KeywordsParams = '<? echo (isset($_REQUEST["keywords"]) ? $_REQUEST["keywords"] : ""); ?>';
		var MAIN_DOMAIN ='<? echo MAIN_DOMAIN; ?>';
	</script>
	<script type="text/javascript" src="bexi_ad_selector_service.js"></script>
</head>
<body>
<div class="main_selector">
	<!-- Next and previous buttons -->
	<a class="selector_prev hidden" onclick="plusSlides(1)"></a>
	<a class="selector_next" onclick="plusSlides(-1)"></a>
	<div class="bexi_sliders" id="modu_sliders" >
	<div class="pre-thumbnail" id="pre-thumbnail">
	
	</div>
	</div>
</div>
</body>
</html>
