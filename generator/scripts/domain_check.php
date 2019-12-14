<?
set_include_path('/var/www/generator.bexi.co/public_html/product_backend/generator/');
include "config.php";
require 'vendor/autoload.php';
include "api/v1/emails.php";

//echo "Tiempo 2 : ".(microtime(true) - $timeini)."<br>";

date_default_timezone_set('UTC');

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

$params = [
    'TableName' => "modu_subdomains",
    "KeyConditionExpression"=> "domain_status = :dstatus",
    "IndexName" => "domain_status-index",
    "ExpressionAttributeValues"=> [
        ":dstatus" =>  ["S" => "0"]
    ]
];

$result = $dynamodb->query($params);

foreach ($result['Items'] as $subd)
{
	$subdomain = $marshaler->unmarshalValue($subd["subdomain"]);
	$deliverable = $marshaler->unmarshalValue($subd["deliverable_id"]);		
	 if ( gethostbyname($domain) != $domain ) {

		$key = $marshaler->marshalJson('
		    {
		        "subdomain" : "' . $subdomain . '"
		    }
		');

		$eav = $marshaler->marshalJson('
		    {
		        ":st": "1"
		    }
		');

		//print_r($key);

		$params = [
		    'TableName' => "modu_subdomains",
		    'Key' => $key,
		    'UpdateExpression' => 
		        'set domain_status = :st',
		    'ExpressionAttributeValues'=> $eav,
		    'ReturnValues' => 'UPDATED_NEW'
		];

		//print_r($params);

		$result2 = $dynamodb->updateItem($params);

	    SendEmail(6,-1, $deliverable);

	 	echo $subdomain." DNS Record found";
	 }
	 else {
	 	echo $subdomain."NO DNS Record found";
	 }
}


?>