<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form name="form" action="" method="post">
        <input type="text" name="block" id="block" value="980">
    </form>
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
        ":vid" =>  ["S" => $_POST['block'];]
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

echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<script src="includes/jquery-3.4.1.min.js"></script>';
echo '<link rel="stylesheet" href="includes/jquery-ui.min.css">';
echo '<link rel="stylesheet" href="./css/bexi_panel.css" >';
echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';

/**************   ICON FONTS **************/
echo'<script src="https://kit.fontawesome.com/2fd6605c8f.js" crossorigin="anonymous"></script>';

//echo'<link rel="stylesheet" type="text/css" href="load_css.php?file=./css/bexi.php&id='.$id_color.'&fontid='.$FontId.'" id="mod_css_0" >'; 

/**************   jquery dialog  **************/
echo '<script src="includes/jquery-ui.min.js"></script>';
echo '<link rel="stylesheet" type="text/css" href="includes/jquery-ui.theme.css" >';

/**************   jquery colorpicker  **************/
echo '<link rel="stylesheet" type="text/css" media="all" href="includes/css/jquery.minicolors.css">
      <script type="text/javascript" src="includes/jquery.minicolors.min.js"></script>';

/**************   PAGINATION **************/
echo '<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>';
echo '<script src="includes/pagination.js"></script>';
echo'<link rel="stylesheet" type="text/css" href="includes/css/bs-pagination.css" >';

/************** GOOGLE FONTS  FROALA EDITOR **************/
echo'<script src="includes/visibility.js"></script>';
echo'<script src="./config.js"></script>';

echo'<link rel="stylesheet" type="text/css" href="css/bexi.css" >';

echo'<link rel="stylesheet" type="text/css" href="includes/css/bexi_editor.css" >';
echo '<script type="text/javascript"  src="includes/bexi_editor.js"></script>';

//echo $FontImport;
echo "\r\n";
?>
    
<?
echo "</head>";
echo "\r\n";

echo "<body>";
echo "<div id='modu_main'>";
echo $contenido;
echo  "</div>";

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

</body>
</html>

