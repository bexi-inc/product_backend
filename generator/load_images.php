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

$filters = [
    'featured' => true,
    'w'        => $_REQUEST["w"],
    'h'        => $_REQUEST["w"]
];

$data = Crew\Unsplash\Photo::random($filters);

print_r($data);
 /* $response = FroalaEditor_Image::delete($_POST['src']);*/
  echo stripslashes(json_encode('Success'));
}
catch (Exception $e) {
  http_response_code(404);
}

?>