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


$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'modu_themes_categories';

$eav = $marshaler->marshalJson('
    {
        ":id": "1" 
    }
');

$params = [
    'TableName' => $tableName,
    'KeyConditionExpression' => 'id = :id',
    'ExpressionAttributeValues'=> $eav
];

try {
    $result = $dynamodb->query($params);

    if (count($result['Items'])>0)
    {
    	$ThemeCat = $marshaler->unmarshalValue($result['Items'][0]['themes']);
    }

    $tableName = 'modu_themes';

    $Themes= [];

    foreach ($ThemeCat  AS $CatId)
    {
    	$eav = $marshaler->marshalJson('
		    {
		        ":id": "'.$CatId.'" 
		    }
		');

    	$params = [
			    'TableName' => $tableName,
			    'KeyConditionExpression' => 'id = :id',
			    'ExpressionAttributeValues'=> $eav
		];


		$res2 = $dynamodb->query($params);

		if (count($res2['Items'])>0)
		{
			$Theme["id"] = $marshaler->unmarshalValue($res2['Items'][0]['id']);
			$Theme["name"] = $marshaler->unmarshalValue($res2['Items'][0]['theme_name']);
			$Theme["css_file"] = $marshaler->unmarshalValue($res2['Items'][0]['css_file']);
			$Themes[] =$Theme;
		}

    }

    //print_r($Themes);

    $code = "";

    $TableName = "modu_contenblocks";

    $eav = $marshaler->marshalJson('
		{
		    ":tp": "hero" 
		}
	');

    $params = [
		'TableName' => $tableName,
		'IndexName' => "type-index",
		'KeyConditionExpression' => 'type = :tp',
		'ExpressionAttributeValues'=> $eav
	];


	$res3 = $dynamodb->query($params);

	if (count($res3['Items'])>0)
	{
		$Idcb = array_rand ($res3['Items']);
		$code .= $marshaler->unmarshalValue($res3['Items'][$Idcb]['code_html']);
	}


} catch (DynamoDbException $e) {
    echo "Unable to query:\n";
    echo $e->getMessage() . "\n";
}





?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script
      src="https://code.jquery.com/jquery-3.4.1.min.js"
      integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
      crossorigin="anonymous"
    ></script>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
    <script
      src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
      integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
      crossorigin="anonymous"
    ></script>

    <script type="text/javascript">
      var ModuThemes = [
      <?
      	foreach ($Themes as $t)
      	{
      		echo '{ "'.$t["name"].'": "/'.$t["css_file"].'" },';
      	}
      ?>
      ];
    </script>

    <link rel="stylesheet" id="theme-style" href="/theme1.css" />
    <script src="/theme-switcher.js"></script>
  </head>
  <body>
    <!--Hero generic-->
    <div id="modu_main">
      <? echo $code; ?>
      <div class="bexi_module" id="%id%">
        <div
          class="bexi_container"
          style="padding-top: 266px; padding-bottom: 307px;"
        >
          <div
            class="row no-gutters mx-auto"
            style="
              justify-content: center;
              align-items: center;
              text-align: center;
              max-width: 1440px;
            "
          >
            <div class="col-12 no-gutters">
              <h2 id="%id%" style="font-weight: bold;">
                Benefits 1 Generic Content Block
              </h2>
            </div>
          </div>
        </div>
      </div>
      <div class="bexi_module" id="%id%">
        <div
          class="bexi_container"
          style="padding-top: 138px; padding-bottom: 139px;"
        >
          <div
            class="row no-gutters mx-auto"
            style="
              justify-content: center;
              align-items: center;
              text-align: center;
              max-width: 1440px;
            "
          >
            <div class="col-12 no-gutters">
              <div style="margin-bottom: 30px;">
                <button
                  id="%id%"
                  type="submit"
                  style="height: 50px; width: 200px;"
                >
                  Call to Action
                </button>
              </div>
              <p id="%id%" style="color: #818181; font-size: 18px;">
                Footer 1 Generic <a href="">Content</a> Block
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
