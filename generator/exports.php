<?
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

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
//$CodeId = (isset($_REQUEST["devid"]) ? $_REQUEST["devid"] :  microtime(true));

if (!isset($_REQUEST["devid"]))
{
    die("No Deliverable Id");
}

    $params = [
        'TableName' => "modu_deliverables",
        "KeyConditionExpression"=> "deliverable_id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $_REQUEST["devid"]]
        ]
    ];

     $result = $dynamodb->query($params);
     $project_id = $marshaler->unmarshalValue($result['Items'][0]["project_id"]);
     $contenido =  gzuncompress(base64_decode($marshaler->unmarshalValue($result['Items'][0]["html_code"])));


    $params = [
        'TableName' => "modu_projects",
         "KeyConditionExpression"=> "project_id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $project_id]
        ]
    ];

     $result2 = $dynamodb->query($params);
     $user_id = $marshaler->unmarshalValue($result2['Items'][0]["user_id"]);
     $project_name = $marshaler->unmarshalValue($result2['Items'][0]["project_name"]);


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

    echo '<link rel="stylesheet" href="files/theme.css">';

    echo '<script src="files/jquery-3.4.1.min.js"></script>';
    echo '<link rel="stylesheet" href="files/jquery-ui.min.css">';
    
    echo '<link rel="stylesheet" href="files/bexi.css">';

    echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
    echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
    echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';


    



    echo'<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" >';
    /**************   ICON FONTS **************/
    echo'<script src="https://kit.fontawesome.com/2fd6605c8f.js" crossorigin="anonymous"></script>';

    echo'<link rel="stylesheet" type="text/css" href="css/bexi.css" >';
    echo "\r\n";
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
    ob_end_clean();

    $_REQUEST["projectid"] = $project_id;

    //$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');

    $doc = new DOMDocument();
    $doc->loadHTML($code);
    $tags = $doc->getElementsByTagName('img');
    $images = [];
    foreach ($tags as $tag) {
        $old_src = $tag->getAttribute('src');
        if (substr( $old_src, 0, 5 ) === "./img")
        {
            $img = [];
            $img["old_src"] = $old_src;
            $filename = pathinfo($old_src,PATHINFO_BASENAME  );
            $img["filename"] = $filename;
            $img["new_src"] = "./files/img/".$filename;
            $tag->setAttribute('src',$img["new_src"]);
            $images[] = $img;
        }
        $pos = strpos(pathinfo($old_src,PATHINFO_DIRNAME),"uploads.getmodu.com");
        if($pos===true){
            $new_src_url = './files/imgs/'.pathinfo($old_src,PATHINFO_BASENAME);
            $tag->setAttribute('src',$new_src_url);
        }
    }
    $code = $doc->saveHTML();

/*
    $dom = new DOMDocument();
    libxml_use_internal_errors( True );
    $dom->loadXML($code);//Open xml to manage
    $dom->formatOutput = True;

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query('channel/item/description');

    foreach( $nodes as $node )
    {
        $html = new DOMDocument();
        $html->loadHTML( $node->nodeValue );
        $src = $html->getElementsByTagName( 'img' )->item(0)->getAttribute('src');
    }
*/


$PATH = PAHTSERVER;
     
if (!file_exists(PAHTSERVER.$user_id)) {
    mkdir($path.$user_id, 0777, true);
}

$PATH = $PATH.$user_id;

if (!file_exists($PATH."/".$project_id)) {
     mkdir($PATH."/".$project_id, 0777, true);
}

$PATH= $PATH."/".$project_id . "/";

if (!file_exists($PATH."/".$_REQUEST["devid"])) {
    mkdir($PATH."/".$_REQUEST["devid"], 0777, true);
}

$PATHBASE = $PATH."/".$_REQUEST["devid"]."/";

unlink($PATHBASE.$project_name.".zip");

$PATH = $PATH."/".$_REQUEST["devid"]."/PUBLISH/";

if (!file_exists($PATH)) {
     mkdir($PATH, 0777, true);
}else{
    $files = new RecursiveIteratorIterator(
              new RecursiveDirectoryIterator($PATH,RecursiveDirectoryIterator::SKIP_DOTS),
              RecursiveIteratorIterator::CHILD_FIRST
            );
    foreach($files as $fileinfo){
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    rmdir($directory);
    clearstatcache();
    mkdir($PATH, 0777, true);
}

if (!file_exists($PATH."/files/")) {
     mkdir($PATH."/files/", 0777, true);
}

$PATHIMG = $PATH."/files/img/";

if (!file_exists($PATHIMG)) {
     mkdir($PATHIMG, 0777, true);
}

$PATHFILES= $PATH."/files/";

ob_start();
include("load_theme.php");
$file_css = ob_get_contents ();
ob_end_clean();

$indexfile = fopen($PATH."index.html", "w") or die("Unable to open file!");
fwrite($indexfile, $code);
fclose($indexfile);

$myfile = fopen($PATHFILES."theme.css", "w") or die("Unable to open file!");
fwrite($myfile, $file_css);
fclose($myfile);

copy("includes/jquery-ui.min.css", $PATHFILES."jquery-ui.min.css" );
copy("includes/jquery-3.4.1.min.js", $PATHFILES."jquery-3.4.1.min.js" );
copy("css/bexi.css", $PATHFILES."bexi.css" );

foreach ($images as $img)
{
    copy($img["old_src"], $PATHIMG.$img["filename"] );
}

$fileZip = $PATHBASE.$project_name.".zip" ;

zipme($PATH,$PATHBASE.$fileZip);

//print_r($images);


header("Content-type: application/zip"); 
header("Content-Disposition: attachment; filename=$fileZip");
header("Content-length: " . filesize($fileZip));
header("Pragma: no-cache"); 
header("Expires: 0"); 
readfile("$fileZip");
?>