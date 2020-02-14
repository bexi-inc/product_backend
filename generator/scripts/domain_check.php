<?
set_include_path('/var/www/generator.bexi.co/public_html/product_backend/generator/');
//set_include_path('/var/www/generator.bexi.co/public_html/product_backend/generator/api/v1/');

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
	//$dns_domain = $subdomain.".".MAIN_DOMAIN;
	$dns_domain = $subdomain.".getmodu.com";
	$deliverable = $marshaler->unmarshalValue($subd["deliverable_id"]);

	echo $dns_domain. "  ".gethostbyname($dns_domain);
	 if ( gethostbyname($dns_domain) != $dns_domain ) {

	 	$params = [
            'TableName' => "modu_deliverables",
            "KeyConditionExpression"=> "deliverable_id = :id",
            "ExpressionAttributeValues"=> [
                ":id" =>  ["S" => $deliverable]
            ]
        ];

        $result2 = $dynamodb->query($params);


		$key = $marshaler->marshalJson('
		    {
		        "deliverable_id " : "' . $deliverable . '",
		        "project_id " : "'.$result2['Items'][0]["project_id"].'"
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
		try
		{
			$key = $marshaler->marshalJson('
			    {
			        "deliverable_id" : "' . $deliverable . '"
			    }
			');


			$params = [
			    'TableName' => "modu_deliverables",
			    'Key' => $key,
			    'UpdateExpression' => 
			        'set domain_status = :st',
			    'ExpressionAttributeValues'=> $eav,
			    'ReturnValues' => 'UPDATED_NEW'
			];

			//print_r($params);

			$result2 = $dynamodb->updateItem($params);
		} catch (Exception $e) {
			print_r($e->getMessage());
		}

		


	    SendEmail(6,-1, $deliverable);

	 	echo $subdomain." DNS Record found";
	 }
	 else {
	 	echo $subdomain."NO DNS Record found";
	 }
}


?>