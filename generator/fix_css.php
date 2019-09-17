<?php

include "includes/global.php";
include "config.php";
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => 'us-east-2',
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();


$params = [
    'TableName' => "components"
    ];

    $result = $dynamodb->scan($params);

    for ($i = 0; $i <count($result['Items']); $i++) {
	    $contenido.=$marshaler->unmarshalValue($result['Items'][$i]['html']);
	    $css=$marshaler->unmarshalValue($result['Items'][$i]["css"]);
	    $css_data = file_get_contents ($css);
	    $css_php = substr($css, 0, -3) . 'php';
	    echo "$i : $css_php<br>";
	    //echo $css_data;
	    $pos = strpos ($css_data,"rgba(246, 175, 0, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(246, 175, 0, 1.0)", "%color1%", $css_data);
	    }

	    //rgba(246, 135, 0, 1.0);
	    $pos = strpos ($css_data,"rgba(246, 135, 0, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(246, 135, 0, 1.0)", "%color1%", $css_data);
	    }

	    //rgba(246, 175, 0, 1.0);
	    $pos = strpos ($css_data,"rgba(246, 175, 0, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(246, 175, 0, 1.0)", "%color1%", $css_data);
	    }


	    $pos = strpos ($css_data,"rgba(0, 95, 120, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(0, 95, 120, 1.0)", "%color2%", $css_data);
	    }

	    $pos = strpos ($css_data,"rgba(129, 129, 129, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(129, 129, 129, 1.0)", "%color3%", $css_data);
	    }

	    //"Helvetica"

	    $pos = strpos ($css_data,'"Helvetica"');
	    if ($pos !== false) {
	    	echo "Font Encontrado ". $pos;
	    	$css_data = str_replace('"Helvetica"', "%font1%", $css_data);
	    }
	    

	    file_put_contents ($css_php, $css_data);
	  
	}



	/*
		Bexi CSS
	*/
		$css="css/bexi.css";
		$css_data = file_get_contents ($css);
	    $css_php = substr($css, 0, -3) . 'php';
	    echo "$i : $css_php<br>";
	    //echo $css_data;
	    $pos = strpos ($css_data,"rgba(246, 175, 0, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(246, 175, 0, 1.0)", "%color1%", $css_data);
	    }

	    //rgba(246, 135, 0, 1.0);
	    $pos = strpos ($css_data,"rgba(246, 135, 0, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(246, 135, 0, 1.0)", "%color1%", $css_data);
	    }

	    //rgba(246, 175, 0, 1.0);
	    $pos = strpos ($css_data,"rgba(246, 175, 0, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(246, 175, 0, 1.0)", "%color1%", $css_data);
	    }


	    $pos = strpos ($css_data,"rgba(0, 95, 120, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(0, 95, 120, 1.0)", "%color2%", $css_data);
	    }

	    $pos = strpos ($css_data,"rgba(129, 129, 129, 1.0)");
	    if ($pos !== false) {
	    	echo "Color Encontrado ". $pos;
	    	$css_data = str_replace("rgba(129, 129, 129, 1.0)", "%color3%", $css_data);
	    }

	    //"Helvetica"

	    $pos = strpos ($css_data,'"Helvetica"');
	    if ($pos !== false) {
	    	echo "Font Encontrado ". $pos;
	    	$css_data = str_replace('"Helvetica"', "%font1%", $css_data);
	    }
	    

	    file_put_contents ($css_php, $css_data);

?>