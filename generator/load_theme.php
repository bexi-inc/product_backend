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

print_r($result);


?>