<?

include "config.php";
require 'vendor/autoload.php';


// Delete the image.
try {

function clean($string) {
  $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

  return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}



Crew\Unsplash\HttpClient::init([
    'applicationId' => $unspash_appid,
    'secret'        => $unspash_secret,
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);

$npage = (isset($_REQUEST["npag"]) ? $_REQUEST["npag"] : 1);

$cant = (isset($_REQUEST["items"]) ? $_REQUEST["items"] : 10);

$data = Crew\Unsplash\Search::photos($_REQUEST["key"], $npage, $cant);



//print_r($data->getResults());


foreach ($data->getResults() as $photo)
{

    //print_r($photo);

	$imgs[] = array(
    	"url" => $photo["urls"]["regular"],
    	"thumb" => $photo["urls"]["thumb"],
        "alt_description" => clean($photo["alt_description"])
	);

}

$res["total"] =  $data->getTotalPages();
$res["new_id"] = uniqid("bexi_img_");
$res["images"] = $imgs;
//print_r($imgs);
 /* $response = FroalaEditor_Image::delete($_POST['src']);*/
  echo stripslashes(json_encode($res));
}
catch (Exception $e) {
  http_response_code(404);
}

?>