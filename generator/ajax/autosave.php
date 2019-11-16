<?

include "../config.php";
require '../vendor/autoload.php';

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


$Code = $_REQUEST["code"];

if (isset($_REQUEST["projectid"]))
{
	$ProjectID = $_REQUEST["projectid"];


	$Type = 1;

	$key = '
		    {
		        "project_id" : "'.$ProjectID.'",
		        "type" : "'.$Type.'"
		    }
		';

	//print_r($key);

	$updateData='{
		":code" : "'.base64_encode(gzcompress($Code, 7)) .'",
		":dmod" : "'.microtime(true).'"
	}';


	$params = [
	        'TableName' => "modu_deliverables",
	        "IndexName" => "type-project_id-index",
	        'ProjectionExpression' => 'deliverable_id',
	         "KeyConditionExpression"=> "project_id = :pid AND #tp = :vtipo",
	        "ExpressionAttributeValues"=> [
	        	":pid" =>  ["S" => $ProjectID],
	            ":vtipo" =>  ["S" => "1"]
	        ],
	        "ExpressionAttributeNames" =>   
	            [ '#tp' => 'type',
	    		 ]

	];

	    //$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

	    //$contenido="";

	$result = $dynamodb->query($params);

	if (count($result['Items'])>0)
	{
		$devid = $marshaler->unmarshalValue($result['Items'][0]['deliverable_id']);
	}

}elseif (isset($_REQUEST["devid"])){
	$params = [
        "TableName" => "modu_deliverables",
        "KeyConditionExpression"=> "deliverable_id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $_REQUEST["devid"]]
        ]
    ];

    $result = $dynamodb->query($params);

    if (count($result['Items'])>0)
    {
    	$ProjectID =  $marshaler->unmarshalValue($result['Items'][0]["project_id"]);	
    }

    $devid = $_REQUEST["devid"];
}


	$key = '
	    {
	        "deliverable_id" : "'.$devid.'",
	        "project_id" : "'.$ProjectID.'"
	    }
	';


	//print_r($key);

	$key = $marshaler->marshalJson($key);

	$eav = $marshaler->marshalJson($updateData);

	$params = [
	    'TableName' => "modu_deliverables",
	    'Key' => $key,
	    'UpdateExpression' => 'set html_code = :code, date_modified = :dmod ',
	    'ExpressionAttributeValues'=> $eav,
	    'ReturnValues' => 'UPDATED_NEW'
	];

	//$paramsNoms["#type"] = "type";
	//$params ["ExpressionAttributeNames"] = $paramsNoms;
	//$params["IndexName"] = $IndexName;

	/*if ($ExpressionNames)
	{
		
	}*/

	//print_r($params);
	//$ret["error"]="";
	//try {
	   
	//print_r($params);
	try {
		$result = $dynamodb->updateItem($params);
		$res["error"] = 0;
	}
	catch (DynamoDbException $e){
		$res["error"] = 500;
	    $res["error_msj"] = "Unable to update";
	}


echo json_encode($res);

?>