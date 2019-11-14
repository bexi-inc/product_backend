<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$timeini = microtime(true);
//header( 'X-Frame-Options: ALLOW-FROM http://generator.localhost' );
header( 'X-Frame-Options: ALLOW-FROM http://dashboard.bexi.co' );
header('Access-Control-Allow-Origin: *');  
//https://codepen.io
//echo "Tiempo: ".(microtime(true) - $timeini)."<br>";
include "includes/global.php";
include "includes/utils.php";
include "config.php";
require 'vendor/autoload.php';
include "includes/content_blocks.php";

//echo "Tiempo 2 : ".(microtime(true) - $timeini)."<br>";

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

//echo "Tiempo 3 : ".(microtime(true) - $timeini)."<br>";

$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => $AWS_REGION,
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();



//$iduser = $_REQUEST["user"];
$CodeId = (isset($_REQUEST["devid"]) ? $_REQUEST["devid"] :  microtime(true));

if (isset($_REQUEST["devid"]))
{
    $params = [
        'TableName' => "modu_deliverables",
         "KeyConditionExpression"=> "deliverable_id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $_REQUEST["devid"]]
        ]
    ];

     $result = $dynamodb->query($params);

     $contenido =  gzuncompress(base64_decode($marshaler->unmarshalValue($result['Items'][0]["html_code"])));
}


ob_start();

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


    



    echo'<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" >';
    /**************   ICON FONTS **************/
    echo'<script src="https://kit.fontawesome.com/2fd6605c8f.js" crossorigin="anonymous"></script>';


    echo '<script src="includes/jquery-ui.min.js"></script>';
    echo '<link rel="stylesheet" type="text/css" href="includes/jquery-ui.theme.css" >';

    echo '<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>';
    echo '<script src="includes/pagination.js"></script>';
    echo'<link rel="stylesheet" type="text/css" href="includes/css/bs-pagination.css" >';

    echo'<link rel="stylesheet" type="text/css" href="css/bexi.css" >';
    echo "\r\n";
    //echo '<script type="text/javascript"  src="includes/bexi_v2.js"></script>';
    echo "</head>";
    echo "\r\n";

    echo "<body>";
   
    echo "<div id='modu_main'>";
    echo $contenido;
    echo  "</div>";

    echo "\r\n";

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

    $code = ob_get_contents ();

    ob_clean();


    echo $code ;
?>