<?

include "config.php";
require 'vendor/autoload.php';

include "includes/utils.php";
include "includes/content_blocks.php";

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

$keywords= "";
if (isset($_REQUEST["campaignid"]))
{
	$campaign_id = $_REQUEST["campaignid"];
	$params = [
        'TableName' => "modu_campaigns",
         "KeyConditionExpression"=> "id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $campaign_id]
        ]
    ];

    $result_proj = $dynamodb->query($params);

    if (count($result_proj["Items"])>0)
    {

        if (isset($result_proj['Items'][0]["keywords"]) && !is_null($result_proj['Items'][0]["keywords"]))
        {
            $keywords=$marshaler->unmarshalValue($result_proj['Items'][0]["keywords"]);
        }else
        {
            $keywords="";
		}

		if (isset($result_proj['Items'][0]["recipe_type"]) && !is_null($result_proj['Items'][0]["recipe_type"]))
        {
            $recipe_type=$marshaler->unmarshalValue($result_proj['Items'][0]["recipe_type"]);
        }else
        {
            $recipe_type="1";
		}

    }else{
		$keywords = "";
		$recipe_type="1";
    }
}


?><!DOCTYPE html>
<html>
<head>
	<script src="includes/jquery-3.4.1.min.js"></script>
	<style type="text/css">
			/* Variables */
	:root {
	--thumbnail-width : 1440px;
	--thumbnail-height : 280vh;
	--thumbnail-zoom: 1;
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
		  /*width: calc(var(--thumbnail-width) * var(--thumbnail-zoom));*/
	 /*width: 36%;*/
	  /*height: calc(var(--thumbnail-height) * var(--thumbnail-zoom));
	  	  overflow: hidden;
	  */
	  	  /*border-style: solid;*/	
	.thumbnail-container {
	  height: 100%;
	  display: inline-block;
	  position: absolute;
	  top: 0;
	  background-color: transparent;
	  float: left;
	  height: calc(var(--thumbnail-height)+50px);
		width: calc(var(--thumbnail-width)+50px);
	}

	

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
	  margin:25px;
	  /*opacity: 0.7;*/
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
	}/*	  background-color: rgba(0,0,0,0.3); */

	/* Position the "next button" to the right */
	.selector_next {
	  margin-right:15px;
	  right: 0;
	  border-radius: 3px 0 0 3px;
	  background-image: url("./imgs/right_gray.png");
	  background-position: center; /* Center the image */
	  background-repeat: no-repeat; /* Do not repeat the image */
	  background-size: cover; /* Resize the background image to cover the entire container */
	}

	.selector_prev {
	  margin-left:15px;
	  left: 0;
	  border-radius: 3px 0 0 3px;
	  background-image: url("./imgs/left_gray.png");
	  background-position: center; /* Center the image */
	  background-repeat: no-repeat; /* Do not repeat the image */
	  background-size: cover; /* Resize the background image to cover the entire container */
	}

	/* On hover, add a black background color with a little bit see-through */
	.selector_prev:hover{
		background-image: url("./imgs/left.png");
	}/*background-color: rgba(0,0,0,0.7); */

	.selector_next:hover {
		background-image: url("./imgs/right.png");
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
	.thumbnail{
		height: calc(var(--thumbnail-height)+50px);
		width: calc(var(--thumbnail-width)+50px);
		background-color: transparent;
		position:relative;
	}/*	background-color: #000; */ 


	.FrameSelected {
	  -webkit-box-shadow:0 5px 20px rgba(45, 211, 214,.2),0 -5px 10px rgba(45, 211, 214,.2) !important;
	  box-shadow:0 15px 30px rgba(45, 211, 214,.9),0 5px 20px rgba(45, 211, 214,.9) !important;
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
		var UserParam = <? echo (isset($_REQUEST["user"]) ? $_REQUEST["user"] : "0"); ?>;
		var CampaignIdParam = '<? echo (isset($_REQUEST["campaignid"]) ? $_REQUEST["campaignid"] : "0"); ?>';
		var KeywordsParams = '<? echo (isset($keywords) ? $keywords : ""); ?>';
		var RecipeParams = '<? echo (isset($recipe_type) ? $recipe_type : "1"); ?>';
		var MAIN_DOMAIN ='<? echo MAIN_DOMAIN; ?>';
	</script>
	<script type="text/javascript" src="includes/bexi_ad_selector.js"></script>
</head>
<body>
<div class="main_selector">
	<!-- Next and previous buttons -->
	<a class="selector_prev hidden" onclick="plusSlides(1)"></a>
	<a class="selector_next" onclick="plusSlides(-1)"></a>
	<div class="bexi_sliders" id="modu_sliders" style="width: 95%; margin-left: 5%; position: relative; height: 100%; margin-right: 0%; overflow: hidden;" >
	</div>
</div>
</body>
</html>
