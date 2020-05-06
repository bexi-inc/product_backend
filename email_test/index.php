<?
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


echo "email_test";

?>