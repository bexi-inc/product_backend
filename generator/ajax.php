<?
include "includes/global.php";
include "includes/utils.php";
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;


$credentials = new Aws\Credentials\Credentials('AKIAIDFD4RK34BYBBBGQ', 'eUaOhE0k8m5xcQ7h2iNEg9Gqtam/P8ynSax9P0Qw');

$sdk = new Aws\Sdk([
    'region'   => 'us-east-2',
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();


Crew\Unsplash\HttpClient::init([
    'applicationId' => '8f586bbd9afbc175525c9533bb914ae96194728573867c11c55396f55cb199da',
    'secret'        => 'f50f7a185526a5be957439940f55c6e2b55b25796a42b24527a04e36aa74f9df',
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);

switch ($_REQUEST["cmd"]) {
	case 'GetColorPallete':
		$params = [
		    'TableName' => "css_colors"
		];

		$params = [
		    'TableName' => "css_colors",
		     "KeyConditionExpression"=> "id = :vId",
		    "ExpressionAttributeValues"=> [
		        ":vId" =>  ["N" => $_REQUEST["id"]]
		    ]
		];

		 $result = $dynamodb->query($params);	
		 $color["color1"]=$marshaler->unmarshalValue($result['Items'][0]["color1"])	;
		 $color["color2"]=$marshaler->unmarshalValue($result['Items'][0]["color2"])	;
		 $color["color3"]=$marshaler->unmarshalValue($result['Items'][0]["color3"])	;
		 $color["color4"]=$marshaler->unmarshalValue($result['Items'][0]["color4"])	;
		 echo json_encode($color);
	break;
	case 'GetFontData':
		$params = [
		    'TableName' => "css_fonts"
		];

		$params = [
		    'TableName' => "css_fonts",
		     "KeyConditionExpression"=> "id = :vId",
		    "ExpressionAttributeValues"=> [
		        ":vId" =>  ["N" => $_REQUEST["id"]]
		    ]
		];

		 $result = $dynamodb->query($params);	
		 $color["import"]=$marshaler->unmarshalValue($result['Items'][0]["import"])	;
		 $color["name"]=$marshaler->unmarshalValue($result['Items'][0]["name"])	;
		 $color["usage"]=$marshaler->unmarshalValue($result['Items'][0]["usage"])	;
		 echo json_encode($color);
	break;
	case "GetImagesByKeyDialog":
		$search = $_REQUEST["keyword"];
		$page = 3;
		$per_page = 10;
		$orientation = 'landscape';

		$imgs =  Crew\Unsplash\Search::photos($search, $page, $per_page, $orientation);
		$content = "";
		//print_r($imgs->getResults());
		foreach  ($imgs->getResults() as $img)
		{
			$content .= "<img src='".$img["urls"]["thumb"]."'>";
			$result["imgs"][]= $img["urls"]["thumb"];
		}
		$result["html"]=$content;
		echo json_encode($result);
	break;
	default:
		# code...
		break;
}
?>