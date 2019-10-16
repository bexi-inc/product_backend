<?

require 'vendor/autoload.php';

Crew\Unsplash\HttpClient::init([
	'applicationId'	=> '8f586bbd9afbc175525c9533bb914ae96194728573867c11c55396f55cb199da',
	'secret'		=> 'f50f7a185526a5be957439940f55c6e2b55b25796a42b24527a04e36aa74f9df',
	'callbackUrl'	=> 'https://your-application.com/oauth/callback',
	'utmSource' => 'Bexi Generator'
]);

$filters = [
    'featured' => true,
    'w'        => $_REQUEST["w"],
    'h'        => $_REQUEST["w"]
];

$data = Crew\Unsplash\Photo::all();


print_r($data);


?>
<? echo $data->urls['custom']; ?>