<?

include "includes/global.php";

require 'vendor/autoload.php';


date_default_timezone_set('UTC');

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;


$credentials = new Aws\Credentials\Credentials('AKIAIDFD4RK34BYBBBGQ', 'eUaOhE0k8m5xcQ7h2iNEg9Gqtam/P8ynSax9P0Qw');

$sdk = new Aws\Sdk([
    'region'   => 'us-east-2',
    'version'  => 'latest',
    'credentials' => $credentials
]);


Crew\Unsplash\HttpClient::init([
    'applicationId' => '8f586bbd9afbc175525c9533bb914ae96194728573867c11c55396f55cb199da',
    'secret'        => 'f50f7a185526a5be957439940f55c6e2b55b25796a42b24527a04e36aa74f9df',
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
    'TableName' => "components",
     "KeyConditionExpression"=> "id = :vid",
    "ExpressionAttributeValues"=> [
        ":vid" =>  ["N" => $_REQUEST["id"]]
    ]
];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

$contenido="";

 $result = $dynamodb->query($params);

$contenido=$marshaler->unmarshalValue($result['Items'][0]['html']);
$css[]=$marshaler->unmarshalValue($result['Items'][0]["css"]);

$pos = 0;
$pos2 = 0;
while ( ( $pos = strpos( $contenido, "%img", $pos ) ) !== false ) {
  $pos2 = strpos( $contenido, "%", ($pos + 1) );
 // echo "The letter 'l' was found at position: $pos<br/>";
  $imgtag = substr($contenido,$pos+1,$pos2-$pos - 1);
 // echo $imgtag;

  $imgdata = explode ("|",$imgtag);
  //print_r( $imgdata);

    $filters = [
            'featured' => true,
            'w'        => $imgdata[1],
            'h'        => $imgdata[2]
    ];

    //print_r($filters);
    try
    {
        $data = Crew\Unsplash\Photo::random($filters);
     
        $contenido=substr_replace($contenido,$data->urls['custom'],$pos,$pos2-$pos);
       
    }catch (Crew\Unsplash\Exception $e) {
          //writeErrorLogEntry(basename(__FILE__,'.php'),__LINE__,$e);
    }
     $pos++;
    
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
echo '<link rel="stylesheet" type="text/css" href="./css/bexi.css">';
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';
echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
foreach ($css as $hoja)
{
     $css_php = substr($hoja, 0, -3) . 'php';
	echo '<link rel="stylesheet" type="text/css" href="load_css.php?file='.$css_php.'&id=1&fontid=1">';
	echo "\r\n";
}
//echo $FontImport;
echo "\r\n";

echo "</head>";
echo "\r\n";

echo "<body>";
/*echo "<div class='bexi_panel'>
<h1 style='margin-bottom: 0px;'>Bexi Panel</h1>
<div style='float:left; width: 30%;'>
    <h3>Color pallets</h3>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($result['Items'][$key]["color1"]).";'></div>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($result['Items'][$key]["color2"]).";'></div>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($result['Items'][$key]["color3"]).";'></div>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($result['Items'][$key]["color4"]).";'></div>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($result['Items'][$key]["color5"]).";'></div>
    <br style='clear:both'>
</div>
<div style='float:left; width: 30%;'>
    <h3>Font:</h3>
    <p>".$FontName."</p>
</div>
<div style='float:left; width: 30%;'>
    <form name='frmgenerator' id='frmgenerator'>
    <h3>Tags:</h3>
   <input type='text' name='txttag' id='txttag'>
   <button> Generate </button>
   </form>
</div>
<br style='clear:both'>
<br>
</div>";*/
echo "\r\n";

echo $contenido;
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
