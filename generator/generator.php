<?
session_start();
$timeini = microtime(true);
//echo "Tiempo: ".(microtime(true) - $timeini)."<br>";
include "includes/global.php";
include "includes/utils.php";
include "config.php";
require 'vendor/autoload.php';

//echo "Tiempo 2 : ".(microtime(true) - $timeini)."<br>";

date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

//echo "Tiempo 3 : ".(microtime(true) - $timeini)."<br>";

$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => 'us-east-2',
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

Crew\Unsplash\HttpClient::init([
    'applicationId' => $unspash_appid,
    'secret'        => $unspash_secret,
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);

//echo "Tiempo 4 : ".(microtime(true) - $timeini)."<br>";

unset($_SESSION["modules"]);
unset( $_SESSION["modules"]);



$eav = $marshaler->marshalJson('
    {
        ":vtipo": "hea" 
    }
');

$params = [
    'TableName' => "bexi_prod_contentblock",
    "IndexName" => "type-index",
     "KeyConditionExpression"=> "#tp = :vtipo",
    "ExpressionAttributeValues"=> [
        ":vtipo" =>  ["S" => "hea"]
    ],
    "ExpressionAttributeNames" =>   
        [ '#tp' => 'type' ]
    
];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

$contenido="";

 $result = $dynamodb->query($params);

 //echo "Tiempo 5 : ".(microtime(true) - $timeini)."<br>";

$key = array_rand ($result['Items'],1);
$contenido = substr_replace($marshaler->unmarshalValue($result['Items'][$key]['html_code'])," id='module_hea' ",4,0);
$css[]=$marshaler->unmarshalValue($result['Items'][$key]["file_css"]);

$_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);

//echo "Tiempo 6 : ".(microtime(true) - $timeini)."<br>";

$params = [
    'TableName' => "bexi_prod_contentblock",
    "IndexName" => "type-index",
     "KeyConditionExpression"=> "#tp = :vtipo",
    "ExpressionAttributeValues"=> [
        ":vtipo" =>  ["S" => "int"]
    ],
    "ExpressionAttributeNames" =>   
        [ '#tp' => 'type' ]

];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

//$contenido="";

 $result = $dynamodb->query($params);

 //echo "Tiempo 7 : ".(microtime(true) - $timeini)."<br>";

$key = array_rand ($result['Items'],1);

$ContenidoTmp=setImages($marshaler->unmarshalValue($result['Items'][$key]['html_code']),$_REQUEST["keywords"]);
$contenido .= substr_replace($ContenidoTmp," id='module_".$i."' ",4,0);
$css[]=$marshaler->unmarshalValue($result['Items'][$key]["file_css"]);

$_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);

//echo "Tiempo 8 : ".(microtime(true) - $timeini)."<br>";
        
/******************************************************
************    CALCULAMOS EL CONTENIDO ***************
******************************************************/

$nmods=rand(1,12);
$modulos ="";

$params = [
    'TableName' => "bexi_prod_contentblock",
    "FilterExpression" => "#tp <> :vtipo1 AND #tp<>:vtipo2 AND #tp<>:vtipo3",
    "ExpressionAttributeValues"=> [
        ":vtipo1" =>  ["S" => "foo"],
        ":vtipo2" =>  ["S" => "hea"],
        ":vtipo3" =>  ["S" => "int"]
        ],
    "ExpressionAttributeNames" =>   
        [ '#tp' => 'type' ]
    ];

    $result = $dynamodb->scan($params);

for ($i = 1; $i <= $nmods; $i++) {

    

  //  echo "Tiempo 9 : ".(microtime(true) - $timeini)."<br>";

    $key = array_rand ($result['Items'],1);

    $ContenidoTmp=setImages($marshaler->unmarshalValue($result['Items'][$key]['html_code']),$_REQUEST["keywords"]);
    $contenido .= substr_replace($ContenidoTmp," id='module_".$i."' ",4,0);
    

    $css[]=$marshaler->unmarshalValue($result['Items'][$key]["file_css"]);

    $_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);
    //echo "Tiempo 10 : ".(microtime(true) - $timeini)."<br>";
    
    unset($result['Items'][$key]);     
}

//echo "Tiempo 11 : ".(microtime(true) - $timeini)."<br>";
$params = [
    'TableName' => "bexi_prod_contentblock",
    "IndexName" => "type-index",
     "KeyConditionExpression"=> "#tp = :vtype",
    "ExpressionAttributeValues"=> [
        ":vtype" =>  ["S" => "foo"]
    ],
    "ExpressionAttributeNames" =>   
        [ '#tp' => 'type' ]
];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

 $set_colors = $dynamodb->query($params);

$key = array_rand ($set_colors['Items'],1);
$contenido.=$marshaler->unmarshalValue($set_colors['Items'][$key]['html_code']);
$css[]=$marshaler->unmarshalValue($set_colors['Items'][$key]["file_css"]);

$_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);


// OBTENEMOS EL FONT POR RANDON

$params = [
    'TableName' => "bexi_prod_fonts",
    "IndexName" => "usage-index",
     "KeyConditionExpression"=> "#v_usage = :vusage",
    "ExpressionAttributeValues"=> [
        ":vusage" =>  ["S" => "body"]
    ],
    "ExpressionAttributeNames" => [
        "#v_usage" =>  "usage"
    ]

];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

 $resFonts = $dynamodb->query($params);

$FontKey = array_rand ($resFonts['Items'],1);

$FontName = $marshaler->unmarshalValue($resFonts["Items"][$FontKey]["name"]);
$FontId = $marshaler->unmarshalValue($resFonts["Items"][$FontKey]["id"]);
$FontImport = $marshaler->unmarshalValue($resFonts["Items"][$FontKey]["import"]);

$_SESSION["idfont"] = $FontId;

 $params = [
    'TableName' => "bexi_prod_colors"
];

$Colors = $dynamodb->scan($params);

$key = array_rand ($Colors['Items'],1);

$id_color=$marshaler->unmarshalValue($Colors['Items'][$key]["id"]);

$_SESSION["idcolor"] = $id_color;


$fontmodal="";
foreach ($resFonts['Items'] as $FontChg)
{
    $fontmodal .= "<div style='padding-bottom: 10px;'>
    <div >".$marshaler->unmarshalValue($FontChg["name"])."</div>
    <a href='#modalFont' rel='modal:close' onClick='ChangeFont(".$marshaler->unmarshalValue($FontChg["id"]).");' rel='modal:close'> Change </a>

    </div>";
}

//echo "Time Final:".(microtime(true) - $timeini)."<br>"; 



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

echo '<link rel="stylesheet" href="./css/bexi_panel.css" >';
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';
echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
echo '<link rel="stylesheet" href="includes/jquery-ui.css">';


echo '
    <link rel="stylesheet" href="includes/colorpicker/css/colorpicker.css" type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="includes/colorpicker/css/layout.css" />
    <script type="text/javascript" src="includes/colorpicker/js/colorpicker.js"></script>
    <script type="text/javascript" src="includes/colorpicker/js/eye.js"></script>
    <script type="text/javascript" src="includes/colorpicker/js/utils.js"></script>
    <script type="text/javascript" src="includes/colorpicker/js/layout.js?ver=1.0.2"></script>';

echo "<!-- jQuery Modal -->";
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />';
echo '<style>';
echo '.colorpicker
        {
            z-index:999;
        }';
echo '</style>';
echo '  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
echo'<link rel="stylesheet" type="text/css" href="css/bexi_generator.css" >';
$n=1;
echo'<link rel="stylesheet" type="text/css" href="load_css.php?file=./css/bexi.php&id='.$id_color.'&fontid='.$FontId.'" id="mod_css_0" >'; 


 $js_css .= '<link rel="stylesheet" type="text/css" href="load_css.php?file=./css/bexi.php&id=\' + paletteId + \'&fontid=\' + FontId + \' " id="mod_css_'.$n.'">';

foreach ($css as $hoja)
{
     $css_php = substr($hoja, 0, -3) . 'php';
	echo '<link rel="stylesheet" type="text/css" href="load_css.php?file='.$css_php.'&id='.$id_color.'&fontid='.$FontId.'" id="mod_css_'.$n.'">';
	echo "\r\n";
    $n =  $n + 1;
    $js_css .= '<link rel="stylesheet" type="text/css" href="load_css.php?file='.$css_php.'&id=\' + paletteId + \'&fontid=\' + FontId + \' " id="mod_css_'.$n.'">';
}
echo $FontImport;
echo "\r\n";
?>
<script type="text/javascript">
    var paletteId = <? echo $_SESSION["idcolor"]; ?>;
    var FontId = <? echo $_SESSION["idfont"]; ?>;
</script>

<script type="text/javascript"  src="includes/bexi_v2.js"></script>
<?

echo "</head>";
echo "\r\n";

echo "<body>";

include("bexi_panel.php");


echo "\r\n";
echo "<div class='' style='width:100%; padding-top: 3%;  padding-bottom: 3%;padding-left: 5%; padding-right: 5%; background-color: #ebebeb;'>";
echo "<div class='' style='width:100%; background-color: #fff; border-radius: 15px; -moz-border-radius: 15px;
    -webkit-border-radius: 15px;  overflow:hidden; -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.05); box-shadow: 0 1px 3px rgba(0,0,0,.05);'>";
echo $contenido;
echo "</div>";
echo "</div>";
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
?>
