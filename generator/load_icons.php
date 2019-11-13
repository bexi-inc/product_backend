<?

include "config.php";
require 'vendor/autoload.php';


use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

// Delete the image.
//try {



//echo "Tiempo 3 : ".(microtime(true) - $timeini)."<br>";

$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => $AWS_REGION,
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();


$npage = (isset($_REQUEST["npag"]) ? $_REQUEST["npag"] : 1);

$cant = (isset($_REQUEST["items"]) ? $_REQUEST["items"] : 20);

$key = (isset($_REQUEST["key"]) ? $_REQUEST["key"] : "");

if ($key)
{
    $params = [
        'TableName' => "modu_icons",
         'FilterExpression' => 'contains (desc_text, :txtkey)',
        // "KeyConditionExpression"=> "id = :id AND #usr=:usr",
       "ExpressionAttributeValues"=> [
            ":txtkey" =>  ["S" => $key]
        ]
        /*
        "ExpressionAttributeNames" =>   
            [ '#usr' => 'user' ]*/
        
    ];
}else{
    $params = [
        'TableName' => "modu_icons"
    ];    
}


     $result = $dynamodb->scan($params);


//print_r($result);


$n = 1;

foreach ($result['Items'] as $icon)
{

    //print_r($photo);
    if ($n >= ((($npage-1) * $cant)+1) && $n<=($npage * $cant ))
    {
        //echo $n."=".$npage."<br>";
        $icons[] = array(
            "class" => $marshaler->unmarshalValue($icon["name_class"]),
            "desc" => $marshaler->unmarshalValue($icon["desc_text"])
        );    
    }
	
    $n = $n + 1; 
}

$res["total"] =  count($result['Items']);
//$res["new_id"] = uniqid("bexi_img_");
$res["icons"] = $icons;
//print_r($imgs);
 /* $response = FroalaEditor_Image::delete($_POST['src']);*/
  echo stripslashes(json_encode($res));
/*}
catch (Exception $e) {
  http_response_code(404);
}*/

?>