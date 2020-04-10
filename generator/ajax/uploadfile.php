<?
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
header("Access-Control-Allow-Origin: http://internal.bexi.ai");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
include "../config.php";
require '../vendor/autoload.php';

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

$path = "/var/www/uploads.getmodu.com/public_html/";
$webpath = "http://".PATHWEB."/";


//print_r($_REQUEST);


//print_r($_FILES);

if (!isset($_REQUEST["devid"]))
{
	if (!isset($_REQUEST["userid"]) || !isset($_REQUEST["projectid"]) || !isset($_FILES))
	{
		die("Incorrect params");
	}
}

$idfile =  (!isset($_REQUEST["tagid"])) ? uniqid() : $_REQUEST["tagid"];

if (isset($_REQUEST["devid"]))
{
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
    	$projectid =  $marshaler->unmarshalValue($result['Items'][0]["project_id"]);	
    }

   // print_r($projectid);

    $params = [
        'TableName' => TBL_PROJECTS,
         "KeyConditionExpression"=> "project_id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $projectid]
        ]
    ];

    $result = $dynamodb->query($params);

    if (count($result['Items'])>0)
    {
    	$campaign_id =  $marshaler->unmarshalValue($result['Items'][0]["campaign_id"]);	
    }



     $params = [
        'TableName' => "modu_campaigns",
         "KeyConditionExpression"=> "id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $campaign_id]
        ]
    ];

    $result = $dynamodb->query($params);

    if (count($result['Items'])>0)
    {
        $userid =  $marshaler->unmarshalValue($result['Items'][0]["user_id"]);  
    }
    
}else{
	$userid = $_REQUEST["userid"];
	$projectid = $_REQUEST["projectid"];
}

if (!$idfile || is_null($idfile) || $idfile== "null"||$idfile=="undefined")
{
	$idfile = uniqid();
}

if (!file_exists($path.$userid)) {
    mkdir($path.$userid, 0777, true);
}

if (!file_exists($path.$userid."/".$projectid)) {
    mkdir($path.$userid."/".$projectid, 0777, true);
}

$fullpath= $path.$userid."/".$projectid . "/";

if ($_REQUEST["thumbnail"]==1)
{
    //echo $target_file;

   // print_r($_FILES);
    
    $target_file = $fullpath.basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $target_file = $fullpath."thumbnail.".$imageFileType;
    //$webpath = $webpath.$userid."/".$projectid . "/thumbnail.".$imageFileType;
    //print_r($_FILES);

   // echo $target_file;

    try {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

            
            $key = $marshaler->marshalJson('
                {
                    "project_id ": "' . $projectid . '"
                }
            ');

           
            print_r($key);

            $eav = $marshaler->marshalJson('
                {
                    ":t_path": "'.$target_file.'" 
                }
            ');

            print_r($eav);

            $params = [
                "TableName" => "modu_projects",
                 'Key' => $key,
                 'UpdateExpression' => 
                    'set thumbnail = :t_path',
                 'ExpressionAttributeValues'=> $eav,
                 'ReturnValues' => 'UPDATED_NEW'
            ];

            $result = $dynamodb->updateItem($params);

        }
        //echo "Succeed in setting bucket website configuration.\n";
    } catch (Exception  $e) {
        // Display error message
        echo $e->getMessage();
        echo "\n";
    }
}else{

    $target_file = $fullpath . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $target_file = $fullpath."/".$idfile.".".$imageFileType;
    $webpath = $webpath.$userid."/".$projectid . "/".$idfile.".".$imageFileType;
    //print_r($_FILES);

    try {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        }
        //echo "Succeed in setting bucket website configuration.\n";
    } catch (Exception  $e) {
        // Display error message
        echo $e->getMessage();
        echo "\n";
    }

    $res["src"] = $webpath;
    $res["id"] = $idfile;
    $res["link"]=$webpath;

    echo stripslashes(json_encode($res));
}

?>