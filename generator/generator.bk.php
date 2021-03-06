<?
session_start();
include "includes/global.php";
include "includes/utils.php";
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

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

Crew\Unsplash\HttpClient::init([
    'applicationId' => '8f586bbd9afbc175525c9533bb914ae96194728573867c11c55396f55cb199da',
    'secret'        => 'f50f7a185526a5be957439940f55c6e2b55b25796a42b24527a04e36aa74f9df',
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);

unset($_SESSION["modules"]);
unset( $_SESSION["modules"]);



$eav = $marshaler->marshalJson('
    {
        ":vtipo": "hea" 
    }
');

$params = [
    'TableName' => "components",
    "IndexName" => "tipo-index",
     "KeyConditionExpression"=> "tipo = :vtipo",
    "ExpressionAttributeValues"=> [
        ":vtipo" =>  ["S" => "hea"]
    ]
];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

$contenido="";

 $result = $dynamodb->query($params);

$key = array_rand ($result['Items'],1);
$contenido = substr_replace($marshaler->unmarshalValue($result['Items'][$key]['html'])," id='module_hea' ",4,0);
$css[]=$marshaler->unmarshalValue($result['Items'][$key]["css"]);

$_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);


$params = [
    'TableName' => "components",
    "IndexName" => "tipo-index",
     "KeyConditionExpression"=> "tipo = :vtipo",
    "ExpressionAttributeValues"=> [
        ":vtipo" =>  ["S" => "int"]
    ]
];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

//$contenido="";

 $result = $dynamodb->query($params);

$key = array_rand ($result['Items'],1);

$ContenidoTmp=setImages($marshaler->unmarshalValue($result['Items'][$key]['html']),$_REQUEST["keywords"]);
$contenido .= substr_replace($ContenidoTmp," id='module_".$i."' ",4,0);
$css[]=$marshaler->unmarshalValue($result['Items'][$key]["css"]);

$_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);


        
/******************************************************
************    CALCULAMOS EL CONTENIDO ***************
******************************************************/

$nmods=rand(1,12);
$modulos ="";

for ($i = 1; $i <= $nmods; $i++) {

    $params = [
    'TableName' => "components",
    "FilterExpression" => "tipo <> :vtipo1 AND tipo<>:vtipo2 AND tipo<>:vtipo3",
    "ExpressionAttributeValues"=> [
        ":vtipo1" =>  ["S" => "foo"],
        ":vtipo2" =>  ["S" => "hea"],
        ":vtipo3" =>  ["S" => "int"]
        ]
    ];

    $result = $dynamodb->scan($params);

    $key = array_rand ($result['Items'],1);

    $ContenidoTmp=setImages($marshaler->unmarshalValue($result['Items'][$key]['html']),$_REQUEST["keywords"]);
    $contenido .= substr_replace($ContenidoTmp," id='module_".$i."' ",4,0);
    

    $css[]=$marshaler->unmarshalValue($result['Items'][$key]["css"]);

    $_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);
            
}

$params = [
    'TableName' => "components",
    "IndexName" => "tipo-index",
     "KeyConditionExpression"=> "tipo = :vtipo",
    "ExpressionAttributeValues"=> [
        ":vtipo" =>  ["S" => "foo"]
    ]
];

//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

 $set_colors = $dynamodb->query($params);

$key = array_rand ($set_colors['Items'],1);
$contenido.=$marshaler->unmarshalValue($set_colors['Items'][$key]['html']);
$css[]=$marshaler->unmarshalValue($set_colors['Items'][$key]["css"]);

$_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);


// OBTENEMOS EL FONT POR RANDON

$params = [
    'TableName' => "css_fonts",
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
    'TableName' => "css_colors"
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

    function ChangePalette(NewPallete)
    {
       // alert (paletteId);
        paletteId = NewPallete;
        var params = {
            "cmd" : "GetColorPallete",
            "id" : NewPallete
        }
        $.ajax({
            type: "GET",
            url: 'ajax.php',
            data: params,
            dataType: 'json',
            success: function(data){
                //alert(data.color1);
                $("#color1").css('background-color', data.color1);
                $("#color2").css('background-color', data.color2);
                $("#color3").css('background-color', data.color3);
                $("#color4").css('background-color', data.color4);
                $("#color5").css('background-color', data.color5);

                $('link[id^="mod_css_"]').remove();
                $('head').append('<? echo $js_css; ?>');
                //alert(data.color1);
            }
        });
    }


    function ChangeFont(NewFont)
    {
       // alert (paletteId);
        FontId = NewFont;
        
        var params = {
            "cmd" : "GetFontData",
            "id" : NewFont
        }
        $.ajax({
            type: "GET",
            url: 'ajax.php',
            data: params,
            dataType: 'json',
            success: function(data){
                //alert(data.color1);
                var cssfont = [data.import.slice(0, 6),' id="css_font_1" ', data.import.slice(6)].join('');
                //alert (cssfont);
                $('link[id^="css_font"]').remove();
                $('head').append(cssfont);
                $('#fontname').html(data.name + "");
                $('link[id^="mod_css_"]').remove();
                $('head').append('<? echo $js_css; ?>');
                //alert(data.color1);
            }
        });
    }


    function SearchImages()
    {
        var params = {
            "cmd" : "GetImagesByKeyDialog",
            "keyword" : $('#txtTagImage').val()
        }
        console.log("SearchImages");
        console.log(params);
        $.ajax({
            type: "GET",
            url: 'ajax.php',
            data: params,
            dataType: 'json',
            success: function(data){
                console.log(data);
               $('#divimages').html(data.html);
            }
        });
    }

    $(document).ready(function() {
        $('#txtColor').ColorPicker({
            onSubmit: function(hsb, hex, rgb, el) {
                $(el).val(hex);
                $(el).ColorPickerHide();
                //alert($("#" + $('#ModuleEdit').val()).html());
                $("#" + $('#ModuleEdit').val()).css('background-color', "#" + hex );
                $("#" + $('#ModuleEdit').val()).children().css('background-color', "#" + hex );
                $("#" + $('#ModuleEdit').val()).children().children().css('background-color', "#" + hex );
                $("#" + $('#ModuleEdit').val()).children().children().children().css('background-color', "#" + hex );
            },
            onBeforeShow: function () {
                $(this).ColorPickerSetColor(this.value);
            }
        });
        $( "#dialog" ).dialog({
            autoOpen: false
        });
        $( "#dlgimg" ).dialog({
            autoOpen: false,
            width: "50%",
            maxWidth: "80%"

        });

        $('.bexi_module').click(function(e) {  
         // alert(1);
           var id = $(this).attr('id');
           var color = $( this ).css( "background-color" );
           //alert(RGBAToHexA(color));
           $('#ModuleEdit').val(id);
           $('#txtColor').val(RGBAToHexA(color));
           //alert ( $('#txtColor').val());
         // $( "#dialog" ).dialog('open');
        });

         $('img').dblclick(function(e) {  
             $( "#dlgimg" ).dialog('open');
         });
    });

    function RGBAToHexA(rgba) {
      let sep = rgba.indexOf(",") > -1 ? "," : " ";
      rgba = rgba.substr(5).split(")")[0].split(sep);
                    
      // Strip the slash if using space-separated syntax
      if (rgba.indexOf("/") > -1)
        rgba.splice(3,1);

      for (let R in rgba) {
        let r = rgba[R];
        if (r.indexOf("%") > -1) {
          let p = r.substr(0,r.length - 1) / 100;

          if (R < 3) {
            rgba[R] = Math.round(p * 255);
          } else {
            rgba[R] = p;
          }
        }
      }
      let r = (+rgba[0]).toString(16),
      g = (+rgba[1]).toString(16),
      b = (+rgba[2]).toString(16),
      a = Math.round(+rgba[3] * 255).toString(16);

      if (r.length == 1)
        r = "0" + r;
      if (g.length == 1)
        g = "0" + g;
      if (b.length == 1)
        b = "0" + b;
      if (a.length == 1)
        a = "0" + a;

      return "#" + r + g + b + a;
    }



</script>
<?

echo "</head>";
echo "\r\n";

echo "<body>";
echo "<div id='modalcolor' class='modal'>
  <p>Change Color Pallet</p>";

foreach ($Colors['Items'] as $ColorPal)
{
    echo "<div style='padding-bottom: 10px;'>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($ColorPal["color1"]).";'></div>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($ColorPal["color2"]).";'></div>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($ColorPal["color3"]).";'></div>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($ColorPal["color4"]).";'></div>
    <div class='color_rec' style='background-color: ".$marshaler->unmarshalValue($ColorPal["color5"]).";'></div>
    <div style='float:left; margin-left:20px;'><a href='#modalcolor' rel='modal:close' onClick='ChangePalette(".$marshaler->unmarshalValue($ColorPal["id"]).");' rel='modal:close'> Change </a></div>
    <br style='clear:both'>
    </div>";
}
echo "<a href='#' rel='modal:close'>Close</a> </div>";
echo "</div>";

echo "<div id='modalFont' class='modal'>
  <p>Change Font</p>";
echo $fontmodal;
echo "<a href='#' rel='modal:close'>Close</a> </div>";
echo "</div>";
echo '<div id="dialog" title="Edit Module" style="display : none;">
  <p>Module Options</p>
  <label>Background Color: </label>
  <input type="text" id="txtColor" name="txtColor" value="">
  <input id="ModuleEdit" name="ModuleEdit" value="" type="hidden">
</div>';

echo '<div id="dlgimg" title="Edit Image" style="display : none;">
  <p>Imagen Options</p>
  <input type="text" id="txtTagImage" name="txtTagImage" value="">
 <button onclick="SearchImages()">Search</button>
 <div id="divimages">
 </div>
</div>';






include("bexi_panel.php");








echo "<div class='bexi_panel' style='background-color: #FFF; padding-top: 2%;'>
<div style='float:left; width: 30%;'>
    <h3>Color pallets</h3>
    <div id='color1'  class='color_rec' style='background-color: ".$marshaler->unmarshalValue($Colors['Items'][$key]["color1"]).";' ></div>
    <div id='color2'  class='color_rec' style='background-color: ".$marshaler->unmarshalValue($Colors['Items'][$key]["color2"]).";'></div>
    <div id='color3'  class='color_rec' style='background-color: ".$marshaler->unmarshalValue($Colors['Items'][$key]["color3"]).";'></div>
    <div id='color4'  class='color_rec' style='background-color: ".$marshaler->unmarshalValue($Colors['Items'][$key]["color4"]).";'></div>
    <div id='color5'  class='color_rec' style='background-color: ".$marshaler->unmarshalValue($Colors['Items'][$key]["color5"]).";'></div>
    <div style='float:left; margin-left:20px;'><a href='#modalcolor' rel='modal:open' > Change </a></div>
    <br style='clear:both'>
</div>
<div style='float:left; width: 30%;'>
    <h3>Font:</h3>
    <p id='fontname'>".$FontName." <a href='#modalFont' rel='modal:open' > Change </a></p>
</div>
<div style='float:left; width: 30%;'>
    <form name='frmgenerator' id='frmgenerator'>
    <h3>Tags:</h3>
   <input type='text' name='keywords' id='keywords' value='".$_REQUEST["keywords"]."'>
   <button> Generate </button>
   </form>
</div>
<br style='clear:both'>
<br>
</div>";
echo "\r\n";
echo "<div class='' style='width:100%; padding-top: 3%;  padding-bottom: 3%;padding-left: 5%; padding-right: 5%; background-color: #ebebeb;'>";
echo "<div class='' style='width:100%; background-color: #fff; border-radius: 15px; -moz-border-radius: 15px;
    -webkit-border-radius: 15px;  overflow:hidden;'>";
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
