<?
date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

//echo "Tiempo 3 : ".(microtime(true) - $timeini)."<br>";

$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => 'us-east-2',
    'version'  => 'latest',
    'credentials' => $credentials
]);

$Dynamodb = $sdk->createDynamoDb();
$Marshaler = new Marshaler();

function ExecuteQuery($tableName,$paramsJson, $KeyCondition, $indexname = "")
{
	global $Dynamodb;
	global $Marshaler;
	global $db_prefix;
	$tableName = $db_prefix.$tableName;

	$params = $Marshaler->marshalJson($paramsJson);

	$params = [
	    'TableName' => $tableName,
	    'KeyConditionExpression' => $KeyCondition,
	    'ExpressionAttributeValues'=> $params
	];

	if ($indexname!="")
	{
		$params["IndexName"] = $indexname;
	}

	$ret["error"]="";
	try {
	    $ret["data"] = $Dynamodb->query($params);
	} catch (DynamoDbException $e) {
		 $ret["error"]=$e->getMessage();
	}
	return  $ret;
}

function Insert($tableName, $paramsJson, $useprefix=true)
{
	global $Dynamodb;
	global $Marshaler;
	global $db_prefix;

	

	$item = $Marshaler->marshalJson($paramsJson);

	$params = [
	    'TableName' => ($useprefix ? $db_prefix : '').$tableName,
	    'Item' => $item
	];

	$ret["error"]="";
	try {
	    $result = $Dynamodb->putItem($params);
	    //return true;
	} catch (DynamoDbException $e) {
	    $ret["error"] = $e->getMessage();
	}

	return $ret;
}

function Update($tableName, $KeyJson, $UpdateExpression, $paramsJson ,$ExpressionNames="")
{
	global $Dynamodb;
	global $Marshaler;
	global $db_prefix;

	$key = $Marshaler->marshalJson($KeyJson);

	$eav = $Marshaler->marshalJson($paramsJson);

	$params = [
	    'TableName' => $db_prefix.$tableName,
	    'Key' => $key,
	    'UpdateExpression' => $UpdateExpression,
	    'ExpressionAttributeValues'=> $eav,
	    'ReturnValues' => 'UPDATED_NEW'
	];

	if ($ExpressionNames)
	{
		$params ["ExpressionAttributeNames"] = $ExpressionNames;
	}

	//print_r($params);
	$ret["error"]="";
	try {
	    $result = $Dynamodb->updateItem($params);
	    $ret["result"]=$result['Attributes'];
	} catch (DynamoDbException $e) {
	    $ret["error"]= $e->getMessage();
	}

	return $ret;
}

?>