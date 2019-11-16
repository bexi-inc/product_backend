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
    'TableName' => "modu_projects",
     "KeyConditionExpression"=> "project_id = :vId",
    "ExpressionAttributeValues"=> [
        ":vId" =>  ["S" => $_REQUEST["projectid"]]
    ]
];


$result = $dynamodb->query($params);

if (count($result["Items"])>0)
{
    $colors = $marshaler->unmarshalValue($result["Items"][0]["colors"]);
    print_r($colors);
   
        if ($color["first"])
        {
            $varcol .= "--color-1 : ".$color["first"].";";
        }
        if ($color["second"])
        {
            $varcol .= "--color-2 : ".$color["second"].";";
        }
        if ($color["third"])
        {
            $varcol .= "--color-3 : ".$color["third"].";";
        }
    
}

echo ":root {";
echo $varcol;
echo "}";

?>