<?

require 'vendor/autoload.php';

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => 'us-east-2',
    'version'  => 'latest',
    'credentials' => $credentials
]);


$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

$tableName = 'modu_themes_categories';

$eav = $marshaler->marshalJson('
    {
        ":id": 1 
    }
');

$params = [
    'TableName' => $tableName,
    'KeyConditionExpression' => 'id = :id',
    'ExpressionAttributeValues'=> $eav
];

try {
    $result = $dynamodb->query($params);

    echo "Query succeeded.\n";

    $ThemeCat = $marshaler->unmarshalValue($result['Items']['title']);


    echo $ThemeCat;

    /*foreach ($result['Items'] as $movie) {
        echo $marshaler->unmarshalValue($movie['year']) . ': ' .
             . "\n";
    }*/

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
    <link rel="stylesheet" id="theme-style" href="/theme1.css" />
    <script src="/theme-switcher.js"></script>
  </head>
  <body>
    <!--Hero generic-->
    <div id="modu_main">
      <div class="bexi_module" id="%id%">
        <div
          class="bexi_container"
          style="padding-top: 341.64px; padding-bottom: 492px;"
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
              <h1 id="%id%" style="font-weight: bold;">
                This is the title of your Hero 1<br />
                Generic Content Block
              </h1>
              <div style="margin-top: 43px;">
                <button
                  id="%id%"
                  type="submit"
                  style="height: 50px; width: 200px; border: 0;"
                >
                  Call to Action
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
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
