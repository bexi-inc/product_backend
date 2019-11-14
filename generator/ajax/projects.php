<?

include "../config.php";
require '../vendor/autoload.php';

include "../includes/utils.php";
include "../includes/content_blocks.php";

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

Crew\Unsplash\HttpClient::init([
    'applicationId' => $unspash_appid,
    'secret'        => $unspash_secret,
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);



$cmd = $_REQUEST["cmd"];

switch ($cmd) {
	case "CreateProject":
		   $res = CreateProject($marshaler,$dynamodb,$_REQUEST["user"],"");
	break;
}

 echo stripslashes(json_encode($res));
?>