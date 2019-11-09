<?

$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => $aws_region,
    'version'  => 'latest',
    'credentials' => $credentials
]);


function CreateBucket($subdomain)
{
	global $sdk;
	$ret["error_code"] = "0";
	try {
	    $result = $sdk->createBucket([
		'ACL' => 'public-read',
	        'Bucket' => $subdomain,
	    ]);
	} catch (AwsException $e) {
	    // output error message if fails
	    $ret["error_code"] = "500";
	    $ret["message"] = $e->getMessage();
	   // echo "\n";
	}

	return $ret;
}
?>