<?
require "utils.php";

require "config.php";
require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$credentials = new Aws\Credentials\Credentials(AWS_KEY, AWS_PASS);

$sdk = new Aws\Sdk([
    'region'   => AWS_REGION,
    'version'  => 'latest',
    'credentials' => $credentials
]);



$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'modu_mail_blocks';

$eav = $marshaler->marshalJson('
	{
	    ":tp": "header" 
	}
');

$params = [
	'TableName' => $tableName,
	'IndexName' => "type-index",
	'KeyConditionExpression' => '#tp = :tp',
	'ExpressionAttributeValues'=> $eav,
	"ExpressionAttributeNames" => [ "#tp" => "type" ] 
];

$res3 = $dynamodb->query($params);

if (count($res3['Items'])>0)
{
	$Idcb = array_rand ($res3['Items']);
	$codeh = $marshaler->unmarshalValue($res3['Items'][$Idcb]['code']);
}

$eav = $marshaler->marshalJson('
	{
	    ":tp": "fooder" 
	}
');

$params = [
	'TableName' => $tableName,
	'IndexName' => "type-index",
	'KeyConditionExpression' => '#tp = :tp',
	'ExpressionAttributeValues'=> $eav,
	"ExpressionAttributeNames" => [ "#tp" => "type" ] 
];

$resf = $dynamodb->query($params);

if (count($resf['Items'])>0)
{
	$Idcb = array_rand ($resf['Items']);
	$codef = $marshaler->unmarshalValue($resf['Items'][$Idcb]['code']);
}

$themes = [
        "themes/theme-1.json",
        "themes/theme-2.json",
        "themes/theme-3.json",
    ];

$theme = loadTheme($themes[array_rand($themes)]);
$brandColors = [
    "#FF00BA",
    "#3FC3D5",
    "#2B87A0"
];

 snippet("head.php", [title => "Test Email", backgroundColor => $theme["backgroundColor"]]);

 snippet2($codeh, [align => "left", logoSrc => "http://uploads.getmodu.com/emails/modu-beta-logo.png", logoAlt => "Modu Logo"]);

  snippet2($codef, [brandColors => $brandColors]);

?>