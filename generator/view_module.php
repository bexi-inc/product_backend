<?

include "includes/global.php";
include "config.php";
include "includes/utils.php";
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


Crew\Unsplash\HttpClient::init([
    'applicationId' => $unspash_appid,
    'secret'        => $unspash_secret,
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);




$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();



$eav = $marshaler->marshalJson('
    {
        ":vtipo": "hea" 
    }
');

$params = [
    'TableName' => "bexi_prod_contentblock",
     "KeyConditionExpression"=> "id = :vid",
    "ExpressionAttributeValues"=> [
        ":vid" =>  ["S" => $_REQUEST["id"]]
    ]
];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

$contenido="";

 $result = $dynamodb->query($params);

$contenido=$marshaler->unmarshalValue($result['Items'][0]['html_code']);
$css[]=$marshaler->unmarshalValue($result['Items'][0]["file_css"]);

$contenido = setImages($contenido,"");


$pos = 0;
$pos2 = 0;
while ( ( $pos = strpos( $contenido, "%id%", $pos ) ) !== false ) {
  $pos2 = strpos( $contenido, "%", ($pos + 1) );
  $idrand = uniqid('bexi_');
  $contenido=substr_replace($contenido,$idrand,$pos,4);
}



echo "<!doctype html>";
echo "\r\n";
echo "<html>";
echo "\r\n";
echo "<head>";
echo "\r\n";
echo "<meta charset='utf-8'>";
echo "\r\n";
echo "<title>Bexi DNA Project</title>";
echo "\r\n";
echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';
echo'<link rel="stylesheet" type="text/css" href="css/bexi_generator.css" >';
echo '<link rel="stylesheet" type="text/css" href="./css/bexi.css">';

 //   <link rel="stylesheet" href="includes/colorpicker/css/colorpicker.css" type="text/css" />
 //  <link rel="stylesheet" media="screen" type="text/css" href="includes/colorpicker/css/layout.css" />
 echo '    <script type="text/javascript" src="includes/colorpicker/js/colorpicker.js"></script>
    <script type="text/javascript" src="includes/colorpicker/js/eye.js"></script>
    <script type="text/javascript" src="includes/colorpicker/js/utils.js"></script>
    <script type="text/javascript" src="includes/colorpicker/js/layout.js?ver=1.0.2"></script>';


/**************   FROALA EDITOR **************/

echo '<link href="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/js/froala_editor.pkgd.min.js"></script>';



echo'<link rel="stylesheet" type="text/css" href="css/bexi_generator.css" >';

echo'<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" >';
//echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.js"></script>';
//echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />';
echo '<script type="text/javascript"  src="includes/bexi_editor.js"></script>';

/**************   ICON FONTS **************/
//echo '<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">';
//echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.js"></script>';
echo'<script src="https://kit.fontawesome.com/2fd6605c8f.js" crossorigin="anonymous"></script>';

foreach ($css as $hoja)
{
     $css_php = substr($hoja, 0, -3) . 'php';
	echo '<link rel="stylesheet" type="text/css" href="load_css.php?file='.$css_php.'&id=1&fontid=1">';
	echo "\r\n";
}
//echo $FontImport;
echo "\r\n";
?>
<script src="includes/bexi.js" ></script>
<?
echo "</head>";
echo "\r\n";

echo "<body>";
echo $contenido;

echo ' <script>
            anima_isHidden = function(e) {
                if (!(e instanceof HTMLElement)) return !1;
                if (getComputedStyle(e).display == "none") return !0; else if (e.parentNode && anima_isHidden(e.parentNode)) return !0;
                return !1;
            };
            anima_loadAsyncSrcForTag = function(tag) {
                var elements = document.getElementsByTagName(tag);
                var toLoad = [];
                for (var i = 0; i < elements.length; i++) {
                    var e = elements[i];
                    var src = e.getAttribute("src");
                    var loaded = (src != undefined && src.length > 0 && src != "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");
                    if (loaded) continue;
                    var asyncSrc = e.getAttribute("anima-src");
                    if (asyncSrc == undefined || asyncSrc.length == 0) continue;
                    if (anima_isHidden(e)) continue;
                    toLoad.push(e);
                }
                toLoad.sort(function(a, b) {
                    return anima_getTop(a) - anima_getTop(b);
                });
                for (var i = 0; i < toLoad.length; i++) {
                    var e = toLoad[i];
                    var asyncSrc = e.getAttribute("anima-src");
                    e.setAttribute("src", asyncSrc);
                }
            };
            anima_pauseHiddenVideos = function(tag) {
                var elements = document.getElementsByTagName("video");
                for (var i = 0; i < elements.length; i++) {
                    var e = elements[i];
                    var isPlaying = !!(e.currentTime > 0 && !e.paused && !e.ended && e.readyState > 2);
                    var isHidden = anima_isHidden(e);
                    if (!isPlaying && !isHidden && e.getAttribute("autoplay") == "autoplay") {
                        e.play();
                    } else if (isPlaying && isHidden) {
                        e.pause();
                    }
                }
            };
            anima_loadAsyncSrc = function(tag) {
                anima_loadAsyncSrcForTag("img");
                anima_loadAsyncSrcForTag("iframe");
                anima_loadAsyncSrcForTag("video");
                anima_pauseHiddenVideos();
            };
            var anima_getTop = function(e) {
                var top = 0;
                do {
                    top += e.offsetTop || 0;
                    e = e.offsetParent;
                } while (e);
                return top;
            };
            anima_loadAsyncSrc();
            anima_old_onResize = window.onresize;
            anima_new_onResize = undefined;
            anima_updateOnResize = function() {
                if (anima_new_onResize == undefined || window.onresize != anima_new_onResize) {
                    anima_new_onResize = function(x) {
                        if (anima_old_onResize != undefined) anima_old_onResize(x);
                        anima_loadAsyncSrc();
                    };
                    window.onresize = anima_new_onResize;
                    setTimeout(function() {
                        anima_updateOnResize();
                    }, 3000);
                }
            };
            anima_updateOnResize();
            setTimeout(function() {
                anima_loadAsyncSrc();
            }, 200);
        </script>';
echo "\r\n";
echo "</body>";
echo "\r\n";
echo "</html>";
echo "\r\n";
?>
