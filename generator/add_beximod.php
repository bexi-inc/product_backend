<?
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;


$credentials = new Aws\Credentials\Credentials('', '');

$sdk = new Aws\Sdk([
    'region'   => 'us-east-2',
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$params = [
    'TableName' => "components",
    ];

    $result = $dynamodb->scan($params);

 foreach ($result['Items'] as $module)
 {
 	$html= $marshaler->unmarshalValue($module["html"]);
 	$html =  substr_replace($html," bexi_module",15,0);
 	echo substr($html,0,30);

 	$id = $marshaler->unmarshalValue($module["id"]);
 	$code = $marshaler->unmarshalValue($module["codigo"]);

 	echo $id."->".$code;

 	
	$tableName = 'components';

	$key = $marshaler->marshalJson('
	    {
	        "id": ' . $id . ',
	        "codigo" : "'.$code.'"
	    }
	');

	$data = [
	        ':h' => $html
    	];
    

    $jsonData = json_encode($data);

	$eav = $marshaler->marshalJson($jsonData);

	//print_r ($eav);
	
	$params = [
	    'TableName' => $tableName,
	    'Key' => $key,
	    'UpdateExpression' => 
	        'set html= :h',
	    'ExpressionAttributeValues'=> $eav,
	    'ReturnValues' => 'UPDATED_NEW'
	];

	try {
	    $result = $dynamodb->updateItem($params);
	    echo "Updated item.\n";
	    print_r($result['Attributes']);

	} catch (DynamoDbException $e) {
	    echo "Unable to update item:\n";
	    echo $e->getMessage() . "\n";
	}
 }



?>