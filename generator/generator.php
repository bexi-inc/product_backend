<?
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

Crew\Unsplash\HttpClient::init([
    'applicationId' => $unspash_appid,
    'secret'        => $unspash_secret,
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);

//echo "Tiempo 4 : ".(microtime(true) - $timeini)."<br>";

unset($_SESSION["modules"]);
unset( $_SESSION["modules"]);

$iduser = $_REQUEST["user"];
$CodeId = (isset($_REQUEST["codeid"]) ? $_REQUEST["codeid"] :  microtime(true))

if (isset($_REQUEST["user"]) && isset($_REQUEST["codeid"]))
{
    $params = [
        'TableName' => "bexi_projects_tmp",
         "KeyConditionExpression"=> "id = :id AND #usr=:usr",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => $_REQUEST["codeid"]],
            ":usr" => ["S" => $_REQUEST["user"]]
        ],
        "ExpressionAttributeNames" =>   
            [ '#usr' => 'user' ]
        
    ];

     $result = $dynamodb->query($params);

     $contenido =  gzuncompress(base64_decode($marshaler->unmarshalValue($result['Items'][0]["code"])));
}
else{



    $eav = $marshaler->marshalJson('
        {
            ":vtipo": "hea" 
        }
    ');

    $params = [
        'TableName' => "bexi_prod_contentblock",
        "IndexName" => "type-index",
        'ProjectionExpression' => 'id, #cod, is_content, #tp, file_css, file_html',
         "KeyConditionExpression"=> "#tp = :vtipo",
        "ExpressionAttributeValues"=> [
            ":vtipo" =>  ["S" => "hea"]
        ],
        "ExpressionAttributeNames" =>   
            [ '#tp' => 'type',
                '#cod' => 'code' ]
        
    ];

    //$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    $contenido="";

     $result = $dynamodb->query($params);

     //echo "Tiempo 5 : ".(microtime(true) - $timeini)."<br>";

    $key = array_rand ($result['Items'],1);
    $contenido = substr_replace(GethtmlCode($dynamodb,  $marshaler, $marshaler->unmarshalValue($result['Items'][$key]['id']))," id='module_hea' ",4,0);
    $css[]=$marshaler->unmarshalValue($result['Items'][$key]["file_css"]);

    $_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);

    //echo "Tiempo 6 : ".(microtime(true) - $timeini)."<br>";

    $params = [
        'TableName' => "bexi_prod_contentblock",
        "IndexName" => "type-index",
        'ProjectionExpression' => 'id, #cod, is_content, #tp, file_css, file_html',
         "KeyConditionExpression"=> "#tp = :vtipo",
        "ExpressionAttributeValues"=> [
            ":vtipo" =>  ["S" => "int"]
        ],
        "ExpressionAttributeNames" =>   
            [ '#tp' => 'type',
                '#cod' => 'code' ]

    ];

    //$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

    //$contenido="";

     $result = $dynamodb->query($params);

     //echo "Tiempo 7 : ".(microtime(true) - $timeini)."<br>";

    $key = array_rand ($result['Items'],1);

    $ContenidoTmp=setImages(GethtmlCode($dynamodb,$marshaler,$marshaler->unmarshalValue($result['Items'][$key]['id'])),$_REQUEST["keywords"]);
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
        'ProjectionExpression' => 'id, #cod, is_content, #tp, file_css, file_html',
        "ExpressionAttributeValues"=> [
            ":vtipo1" =>  ["S" => "foo"],
            ":vtipo2" =>  ["S" => "hea"],
            ":vtipo3" =>  ["S" => "int"]
            ],
        "ExpressionAttributeNames" =>   
            [ '#tp' => 'type',
              '#cod' => 'code' ]
        ];

        $result = $dynamodb->scan($params);

    for ($i = 1; $i <= $nmods; $i++) {

        

      //  echo "Tiempo 9 : ".(microtime(true) - $timeini)."<br>";

        $key = array_rand ($result['Items'],1);

        $ContenidoTmp=setImages(GethtmlCode($dynamodb,$marshaler,$marshaler->unmarshalValue($result['Items'][$key]['id'])),$_REQUEST["keywords"]);
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
         'ProjectionExpression' => 'id, #cod, is_content, #tp, file_css, file_html',
         "KeyConditionExpression"=> "#tp = :vtype",
        "ExpressionAttributeValues"=> [
            ":vtype" =>  ["S" => "foo"]
        ],
        "ExpressionAttributeNames" =>   
            [ '#tp' => 'type',
              '#cod' => 'code' ]
        ];


     $set_colors = $dynamodb->query($params);

    $key = array_rand ($set_colors['Items'],1);
    $contenido.=GethtmlCode($dynamodb,$marshaler,$marshaler->unmarshalValue($set_colors['Items'][$key]['id']));
    $css[]=$marshaler->unmarshalValue($set_colors['Items'][$key]["file_css"]);

    //$_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);


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


    $pos = 0;
    $pos2 = 0;
    while ( ( $pos = strpos( $contenido, "%id%", $pos ) ) !== false ) {
      $pos2 = strpos( $contenido, "%", ($pos + 1) );
      $idrand = uniqid('bexi_');
      $contenido=substr_replace($contenido,$idrand,$pos,4);
    }
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


    /**************   FROALA EDITOR **************/

    echo '<link href="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/js/froala_editor.pkgd.min.js"></script>';
    echo'<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/js/third_party/font_awesome.min.js"></script>';


    echo'<link rel="stylesheet" type="text/css" href="css/bexi_generator.css" >';

    echo'<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" >';
    /**************   ICON FONTS **************/
    echo'<script src="https://kit.fontawesome.com/2fd6605c8f.js" crossorigin="anonymous"></script>';
    $n=1;
    //echo'<link rel="stylesheet" type="text/css" href="load_css.php?file=./css/bexi.php&id='.$id_color.'&fontid='.$FontId.'" id="mod_css_0" >'; 


    echo '<script src="includes/jquery-ui.min.js"></script>';
    echo '<link rel="stylesheet" type="text/css" href="includes/jquery-ui.theme.css" >';


    echo '<link rel="stylesheet" type="text/css" media="all" href="includes/css/jquery.minicolors.css">
          <script type="text/javascript" src="includes/jquery.minicolors.min.js"></script>';

    echo '<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>';
    echo '<script src="includes/pagination.js"></script>';
    echo'<link rel="stylesheet" type="text/css" href="includes/css/bs-pagination.css" >';

    echo'<link rel="stylesheet" type="text/css" href="css/bexi.css" >';
    echo $FontImport;
    echo "\r\n";
    ?>
   

    <script type="text/javascript"  src="includes/bexi_v2.js"></script>
    
    <?
    if ($_REQUEST["target"]=="selector")
    {
        echo '<link rel="stylesheet" type="text/css" href="includes/css/bexi_selector.css">';
        echo '<script type="text/javascript"  src="includes/bexi_editor_selector.js"></script>';
    }else{
        echo'<link rel="stylesheet" type="text/css" href="includes/css/bexi_editor.css" >'; 
        echo '<script type="text/javascript"  src="includes/bexi_editor.js"></script>';
    }
    echo "</head>";
    echo "\r\n";

    echo "<body>";

    if ($_REQUEST["target"]=="selector")
    {
        echo "<img id='img_select_project' src='imgs/edit.png' bexi-code='".$CodeId."'>";
    }
    echo '<input type="hidden" id="codeId" name="codeId" value="'.$CodeId.'">';
    echo '<input type="hidden" id="userId" name="userId" value="'.$_REQUEST["user"].'">';
    echo '<div id="dialog-1" title="Titulo" style="display: none;" >
    </div>';

    echo "\r\n";
    /*echo "<div class='' style='width:100%; padding-top: 3%;  padding-bottom: 3%;padding-left: 5%; padding-right: 5%; background-color: #ebebeb;'>";
    echo "<div class='' style='width:100%; background-color: #fff; border-radius: 15px; -moz-border-radius: 15px;
        -webkit-border-radius: 15px;  overflow:hidden; -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.05); box-shadow: 0 1px 3px rgba(0,0,0,.05);'>";
    */
    echo "<div id='modu_main'>";
    echo $contenido;
    echo  "</div>";
    //echo "</div>";
    //echo "</div>";

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


    if (is_numeric($_REQUEST["user"]) && $_REQUEST["user"]>0 && !isset($_REQUEST["codeid"]))
    {
        $data1 = '{
            "id" : "'.$CodeId.'",
            "user" : "'.$_REQUEST["user"].'",
            "code" : "'.base64_encode(gzcompress($contenido, 7)) .'"
        }';

        //$data1 = json_encode($data1);
       // print_r($data1);
        $item = $marshaler->marshalJson($data1);

        $params = [
            'TableName' => 'bexi_projects_tmp',
            'Item' => $item
        ];

        $ret["error"]="";
        try {
            $result = $dynamodb->putItem($params);
            //return true;
        } catch (DynamoDbException $e) {
            $ret["error"] = $e->getMessage();
        }
    }

    echo $code;

?>
