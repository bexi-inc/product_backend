<?php
error_reporting(E_ERROR | E_PARSE);

header("Content-type: text/css; charset: UTF-8");


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
    'TableName' => "project_id",
     "KeyConditionExpression"=> "project_id = :vId",
    "ExpressionAttributeValues"=> [
        ":vId" =>  ["S" => $_REQUEST["projectid"]]
    ]
];


$result = $dynamodb->query($params);

//print_r($result);

/*
$contenido=$marshaler->unmarshalValue($result['Items'][0]['html']);
$css[]=$marshaler->unmarshalValue($result['Items'][0]["css"]);

*/

$css_data = file_get_contents ($_REQUEST["file"]);

$css_data = str_replace("%color1%", $marshaler->unmarshalValue($result['Items'][0]["color1"]), $css_data);
$css_data = str_replace("%color2%", $marshaler->unmarshalValue($result['Items'][0]["color2"]), $css_data);
$css_data = str_replace("%color3%", $marshaler->unmarshalValue($result['Items'][0]["color3"]), $css_data);
$css_data = str_replace("%color4%", $marshaler->unmarshalValue($result['Items'][0]["color4"]), $css_data);
$css_data = str_replace("%color5%", $marshaler->unmarshalValue($result['Items'][0]["color5"]), $css_data);

if (!$_REQUEST["fontid"])
{
	$_REQUEST["fontid"]=1;
}
$params = [
    'TableName' => "bexi_prod_fonts",
     "KeyConditionExpression"=> "id = :vId",
    "ExpressionAttributeValues"=> [
        ":vId" =>  ["S" => $_REQUEST["fontid"]]
    ]
];


$result = $dynamodb->query($params);

$css_data = str_replace("%font1%", '"'.$marshaler->unmarshalValue($result['Items'][0]["name"]).'"', $css_data);

echo $css_data;
?>