<?php
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
    if (count($colors)>0)
    {
        $varcol="";
        if ($colors[0]["first"])
        {
            $varcol .= "--color-1 : ".$colors[0]["first"].";
            ";
        }
        if ($colors[0]["second"])
        {
            $varcol .= "--color-2 : ".$colors[0]["second"].";
            ";
        }
        if ($colors[0]["third"])
        {
            $varcol .= "--color-3 : ".$colors[0]["third"].";
            ";
        }
    }


    $txtcolors = $marshaler->unmarshalValue($result["Items"][0]["txtcolors"]);
    if (count($txtcolors)>0)
    {
        $vartxtcol="";
        if ($txtcolors[0]["first"])
        {
            $vartxtcol .= "--color-text-1 : ".$txtcolors[0]["first"].";
            ";
        }
        if ($txtcolors[0]["second"])
        {
            $vartxtcol .= "--color-text-2 : ".$txtcolors[0]["second"].";
            ";
        }
        
    }

    $fontprimary="";
    $fontprimary = "\n--font-1:'".$marshaler->unmarshalValue($result["Items"][0]["font_primary"])."'\n";
    $fontsecondary="";
    $fontsecondary = "\n--font-2:'".$marshaler->unmarshalValue($result["Items"][0]["font_secondary"])."'\n";
    
}

echo ":root {
            ";
if ($varcol=="")
{
    echo "   --color-1: rgb(246, 135, 0);
            --color-2: rgb(246, 135, 0);
            --color-3: white;";
}else{
    echo $varcol;    
}

if ($vartxtcol=="")
{
    echo "      --color-text-1:black;
                --color-text-2:grey;
    ";
}else{
    echo $vartxtcol;
}

if($fontprimary=="")
{
    echo "\n--font-1:'Montserrat', sans-serif\n";
}else{
    echo $fontprimary;
}

if($fontsecondary=="")
{
    echo "--font-2:'Montserrat', sans-serif\n";
}else{
    echo $fontsecondary;
}
echo "}";

?>