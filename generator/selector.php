<?
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

include "config.php";
require 'vendor/autoload.php';

include "includes/utils.php";
include "includes/content_blocks.php";

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

// Delete the image.
//try {

$type = 1 ; // 1 = Landing page

//echo "Tiempo 3 : ".(microtime(true) - $timeini)."<br>";

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
if (isset($_REQUEST["projectid"]))
{
	$project_id = $_REQUEST["projectid"];
	$params = [
        'TableName' => "modu_projects",
         "KeyConditionExpression"=> "project_id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $project_id]
        ]
    ];

    //print_r($params);
    $result_proj = $dynamodb->query($params);

    if (count($result_proj)>0)
    {
        
        if (isset($result_proj['Items'][0]["keywords"]) && !is_null($result_proj['Items'][0]["keywords"]))
        {
            $keywords=$marshaler->unmarshalValue($result_proj['Items'][0]["keywords"]);

           // print_r($result_proj);
           // echo($logurl);
        }else
        {
            $keywords="";
        }
    }else{
        $keywords = "";
    }
}


/*
	$res = CreateProject($marshaler, $dynamodb,$_REQUEST["user"],$keywords);

	$data["url"]= "http://generator.getmodu.com/generator.php?target=selector&user=".$_REQUEST["user"].'&codeid='.$res["codeid"]."&projectid=".$_REQUEST["projectid"];
	$data["codeid"] = $res["codeid"];

	$Projs[] = $data;

	$res = CreateProject($marshaler, $dynamodb,$_REQUEST["user"],$keywords);

	$data["url"]= "http://generator.getmodu.com/generator.php?target=selector&user=".$_REQUEST["user"].'&codeid='.$res["codeid"]."&projectid=".$_REQUEST["projectid"];
	$data["codeid"] = $res["codeid"];

	$Projs[] = $data;

	$res = CreateProject($marshaler, $dynamodb,$_REQUEST["user"],$keywords);

	$data["url"]= "http://generator.getmodu.com/generator.php?target=selector&user=".$_REQUEST["user"].'&codeid='.$res["codeid"]."&projectid=".$_REQUEST["projectid"];
	$data["codeid"] = $res["codeid"];

	$Projs[] = $data;

	$res = CreateProject($marshaler, $dynamodb,$_REQUEST["user"],$keywords);

	$data["url"]= "http://generator.getmodu.com/generator.php?target=selector&user=".$_REQUEST["user"].'&codeid='.$res["codeid"]."&projectid=".$_REQUEST["projectid"];
	$data["codeid"] = $res["codeid"];

	$Projs[] = $data;
	*/

	//print_r($Projs);
	//die();

?><!DOCTYPE html>
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
	  opacity: 0.7;
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
	  background-image: url("./imgs/right.png");
	}

	.selector_prev {
	  left: 0;
	  border-radius: 3px 0 0 3px;
	  background-image: url("./imgs/left.png");
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
	.thumbnail{
		background-color: #000;
	}
	</style>
	<script type="text/javascript">

		

		var Widthparam = <? echo (isset($_REQUEST["screen_width"]) ? $_REQUEST["screen_width"] : "0"); ?>;
		var Heightparam = <? echo (isset($_REQUEST["screen_height"]) ? $_REQUEST["screen_height"] : "0"); ?>;
		var UserParam = <? echo (isset($_REQUEST["user"]) ? $_REQUEST["user"] : "0"); ?>;
		var ProjectIdParam = '<? echo (isset($_REQUEST["projectid"]) ? $_REQUEST["projectid"] : "0"); ?>';
		var KeywordsParams = '<? echo (isset($keywords) ? $keywords : ""); ?>';

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
		        	}
		        });
		        console.log("Send winnder and looser");

		        console.log (data[0] + '|' + data[1] + '|' + looser);
		         if (window.top != window.self) {
		         	window.top.postMessage(data[0] + '|' + data[1] + '|' + looser, '*')
		         }

		    }
		};

		$( document ).ready(function() {
			/*$("iframe").each(function() {
				//console.log("iframe");
				$(this).attr("id",uniqId());
				//alert($(this).)
			});*/
			
		});

		

	</script>
	<script type="text/javascript" src="includes/bexi_selector.js"></script>
</head>
<body>
<div class="main_selector">
	<!-- Next and previous buttons -->
	<a class="selector_prev" onclick="plusSlides(1)">&#10094;</a>
	<a class="selector_next" onclick="plusSlides(-1)">&#10095;</a>
	<div class="bexi_sliders" id="modu_sliders" style="width: 90%; margin-left: 5%; position: relative; height: 100%; margin-right: 5%; overflow: hidden;" >
		
	</div>
</div>
</body>
</html>
