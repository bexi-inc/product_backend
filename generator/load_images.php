<?

include "config.php";
require 'vendor/autoload.php';


// Delete the image.
try {




Crew\Unsplash\HttpClient::init([
    'applicationId' => $unspash_appid,
    'secret'        => $unspash_secret,
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);



$data = Crew\Unsplash\Search::photos($_REQUEST["key"]);

print_r($data);
foreach ($data as $photo)
{
	$imgs[] = array(
    	"url" => $photo->urls["regular"],
    	"thumb" => $photo->urls["thumb"]
	);

}

//print_r($imgs);
 /* $response = FroalaEditor_Image::delete($_POST['src']);*/
  echo stripslashes(json_encode($imgs));
}
catch (Exception $e) {
  http_response_code(404);
}

?>