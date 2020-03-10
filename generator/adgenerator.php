<?
include "includes/global.php";
include "includes/utils.php";
include "config.php";
require 'vendor/autoload.php';

Crew\Unsplash\HttpClient::init([
    'applicationId' => $unspash_appid,
    'secret'        => $unspash_secret,
    'callbackUrl'   => 'https://your-application.com/oauth/callback',
    'utmSource' => 'Bexi Generator'
]);

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

$sdk = new Aws\Sdk([
    'region'   => $AWS_REGION,
    'version'  => 'latest',
    'credentials' => $credentials
]);

$dynamodb = $sdk->createDynamoDb();
$marshaler = new Marshaler();

if(isset($_REQUEST["cmd"])){

    if($_REQUEST["cmd"]=="CreateAd" && isset($_REQUEST["user"]) && isset($_REQUEST["campaign_id"]) && isset($_REQUEST["recipe"]) ){
        function init_array($m, $n, $value = 0) {
            return array_fill(0, $m, array_fill(0, $n, $value));
        }

        $blocks=init_array(5,5,0);
        /********TAMAÑOS ******/
        //BUTTON 1
        //TEXTO 1 + 2= 5
        //ICON 1
        //IMAGES 3*3=9


        function get_coord($width,$height,$xdim,$ydim){
        $coord[0]=rand(0,$xdim-$width);
        $coord[1]=rand(0,$ydim-$height);
        return $coord;
        }

        function fits_element($width,$height,$xcoord,$ycoord){
            for ($y=0; $y < $height; $y++) {
                for ($x=0; $x < $width; $x++) {
                    if($GLOBALS["blocks"][$ycoord+$y][$xcoord+$x]!==0)
                    {
                        return "false";
                    }
                }
            }
            return "true";
        }

        function put_element($width,$height,$xcoord,$ycoord,$value=1){
            for ($y=0; $y < $height; $y++) {
                for ($x=0; $x < $width; $x++) {
                    $GLOBALS["blocks"][$ycoord+$y][$xcoord+$x]=$value;
                }
            }
        }


        function iterator($width,$height,$xdim,$ydim,$value=1){
            $placed="false";
            $count=0;//to evade infinite loop
            do {
                $coord=get_coord($width,$height,$xdim,$ydim);//get random coordinates
                $placed=fits_element($width,$height,$coord[0],$coord[1]);//check if it is possible to put it in that place
                if($placed==="true")
                {
                    put_element($width,$height,$coord[0],$coord[1],$value);//put it in that place
                    $placed=$coord;
                }
                $count++;
            } while ($count <= 50 && $placed==="false");
            return $placed;
        }


        function main_loop($xdim,$ydim,$recipe){
            $restart="false";
            $recipe_array=split_recipe($recipe);//split the recipe

            //BUTTON
            $resp=search_element($recipe_array,"2");
            if($resp!=="false")
            {
                $prob=100;//probability of introducing it
                if($resp==="optional")
                {
                    $prob=rand(0,100);//change probability
                }

                if($prob>50)
                {
                    $butcoord=iterator(1,1,$xdim,$ydim,1);
                    if($butcoord==="false")
                    {
                        $GLOBALS["blocks"]=init_array($xdim,$ydim,0);//restart the array
                        $restart="true";
                        $out=main_loop($xdim,$ydim,$recipe);//we recursively call the method again
                    }
                    $probClass = rand(1,200);
                    $GLOBALS["buttonClass"] = $probClass>=1 && $probClass<=50 ? 'button-default' : ($probClass>=51 && $probClass<=100 ? 'button-default-2' : ($probClass>=101 && $probClass<=150 ? 'button-outline-1' : 'button-outline-2'));
                }
            }


            if($restart==="false")
            {
                //TEXT 1
                $resp=search_element($recipe_array,"5");
                if($resp!=="false")
                {
                    $prob=100;//probability of introducing it
                    if($resp==="optional")
                    {
                        $prob=rand(0,100);//change probability
                    }

                    if($prob>50)
                    {
                        $text1coord=iterator(3,1,$xdim,$ydim,2);
                        if($text1coord==="false")
                        {
                            $GLOBALS["blocks"]=init_array($xdim,$ydim,0);//restart the array
                            $restart="true";
                            $out=main_loop($xdim,$ydim,$recipe);//we recursively call the method again
                        }
                    }

                }
            }

            if($restart==="false")
            {
                //TEXT 2
                $resp=search_element($recipe_array,"4");
                if($resp!=="false")
                {
                    $prob=100;//probability of introducing it
                    if($resp==="optional")
                    {
                        $prob=rand(0,100);//change probability
                    }

                    if($prob>50)
                    {
                        $text2coord=iterator(2,1,$xdim,$ydim,3);
                        if($text2coord==="false")
                        {
                            $GLOBALS["blocks"]=init_array($xdim,$ydim,0);//restart the array
                            $restart="true";
                            $out=main_loop($xdim,$ydim,$recipe);//we recursively call the method again
                        }
                    }
                }
            }
            if($restart==="false")
            {
                //ICON
                $resp=search_element($recipe_array,"3");
                if($resp!=="false")
                {
                    $prob=100;//probability of introducing it
                    if($resp==="optional")
                    {
                        $prob=rand(0,100);//change probability
                    }

                    if($prob>50)
                    {
                        $iconcoord=iterator(1,1,$xdim,$ydim,4);
                        if($iconcoord==="false")
                        {
                            $GLOBALS["blocks"]=init_array($xdim,$ydim,0);//restart the array
                            $restart="true";
                            $out=main_loop($xdim,$ydim,$recipe);//we recursively call the method again
                        }
                    }
                }

            }
            if($restart==="false")
            {
                //IMAGE
                $resp=search_element($recipe_array,"1");
                if($resp!=="false")
                {
                    $prob=100;//probability of introducing it
                    if($resp==="optional")
                    {
                        $prob=rand(0,100);//change probability
                    }

                    if($prob>50)
                    {
                        $imgcoord=iterator(3,3,$xdim,$ydim,5);
                        if($imgcoord==="false")
                        {
                            $GLOBALS["blocks"]=init_array($xdim,$ydim,0);//restart the array
                            $restart="true";
                            $out=main_loop($xdim,$ydim,$recipe);//we recursively call the method again
                        }
                    }
                }
            }

            if($restart==="false"){
                if(isset($butcoord)){
                    $out["button"]=$butcoord;
                }
                if(isset($text1coord)){
                    $out["text1"]=$text1coord;
                }
                if(isset($text2coord)){
                    $out["text2"]=$text2coord;
                }
                if(isset($iconcoord)){
                    $out["icon"]=$iconcoord;
                }
                if(isset($imgcoord)){
                    $out["img"]=$imgcoord;
                }
            }
            return $out;
        }


        function split_recipe($string){
        return explode(",",$string);
        }

        function search_element($recipe,$element){
            $found="false";
            foreach ($recipe as $key => $value) {
                $pos=strpos($value,$element);
                if($pos!==false)
                {
                    $found="true";
                    //check if its optional
                    $pos=strpos($value,"O");
                    if($pos!==false)
                    {
                        $found="optional";
                    }
                    return $found;
                }
            }
            return $found;
        }

        function get_string_between($string, $start, $end){
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }

        function set_string_between($string,$start, $end,$newstring){
            $string = ' ' . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return '';
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr_replace($string, $newstring, $ini, $len);
        }


        $xdim=1200;//width of final ad
        $ydim=628;//height of final ad

        /***********Get recipe from DB **********/
        $params = [
            'TableName' => "modu_recipes_ads",
             "KeyConditionExpression"=> "id = :id",
            "ExpressionAttributeValues"=> [
                ":id" =>  ["S" => $_REQUEST["recipe"]]
            ]
        ];

        $result_proj = $dynamodb->query($params);

        if (count($result_proj["Items"])>0)
        {
            $recipe=$marshaler->unmarshalValue($result_proj['Items'][0]["elements"]);
        }
        else{
            $recipe="1,2,3,4,5";
        }

        $coords=main_loop(5,5,$recipe);

        $xblock=$xdim/5;
        $yblock=$ydim/5;
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
        echo "\r\n";

        /**************   JQUERY **************/
        echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
        echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
        echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';

        echo'<link rel="stylesheet" type="text/css" href="css/bexi.css" >';

        /**************   FROALA EDITOR **************/
        echo '<script type="text/javascript" >';
        echo 'var FroalaKey = "'.FROALA_KEY.'";';

        echo 'var t1maxchar="'.(($xblock*3)/(0.3*($yblock/2))).'";';
        echo 'var t1size=[8';
        $total=($yblock/3)-8;
        $count=$total/2;
        for ($i=1; $i < $count ; $i++) {
        echo ','.(($i*2)+8);
        }
        echo '];';
        echo 'var t2maxchar="'.(($xblock*2)/(0.15*($yblock/2))).'";';
        echo 'var t2size=[8';
        $total=($yblock/4)-8;
        $count=$total/2;
        for ($i=1; $i < $count ; $i++) {
        echo ','.(($i*2)+8);
        }
        echo '];';
        echo 'var icon_size = []; icon_size[8]=8;';
        $total=($yblock/2)-8;
        $count=$total/2;
        for ($i=1; $i < $count ; $i++) {
        echo 'icon_size['.(($i*2)+8).']='.(($i*2)+8).';';
        }
        echo 'var buttonmaxchar="'.(($xblock*1)/(0.25*($yblock/2))).'";';
        echo 'var buttonsize=[8';
        $total=($yblock/5)-8;
        $count=$total/2;
        for ($i=1; $i < $count ; $i++) {
        echo ','.(($i*2)+8);
        }
        echo '];';
        echo '</script>';
        echo '<link href="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />';
        echo '<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/js/froala_editor.pkgd.min.js"></script>';
        echo '<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/js/third_party/font_awesome.min.js"></script>';
        echo'<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" >';

        /**************   ICON FONTS **************/
        echo'<script src="https://kit.fontawesome.com/2fd6605c8f.js" crossorigin="anonymous"></script>';


        /**************   Jquery dialog **************/
        echo '<link rel="stylesheet" href="includes/jquery-ui.min.css">';
        echo '<script src="includes/jquery-ui.min.js"></script>';
        echo '<link rel="stylesheet" type="text/css" href="includes/jquery-ui.theme.css" >';

        /**************   Jquery palett colors **************/
        echo '<link rel="stylesheet" type="text/css" media="all" href="includes/css/jquery.minicolors.css">
            <script type="text/javascript" src="includes/jquery.minicolors.min.js"></script>';

        /**************   PAGINATION **************/
        echo '<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>';
        echo '<script src="includes/pagination.js"></script>';
        echo'<link rel="stylesheet" type="text/css" href="includes/css/bs-pagination.css" >';

        /**************   EDITOR **************/
        //echo '<script type="text/javascript"  src="includes/ad_editor.js"></script>';
        echo'<style>';
        echo '.bexi_module_ad{
            padding-top:0px;
            padding-bottom: 0px;
            padding-left: 0px;
            padding-right: 0px;
            margin-top: 39px;';
        echo 'width:'. ($xdim+4) .'px;';
        echo 'height:'.($ydim+4) .'px;';
        echo 'background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }';
        echo'</style>';
        echo "</head>";
        echo "\r\n";
        echo "<body>";
        echo "\r\n";
        echo '<div class= "bexi_module_ad" id="maindiv" style="position:relative;border: 2px solid red;background-color:rgba(0, 0, 0, 0.5);" >';
        echo '<div class="transpa-bg" style="background-image: url(\'%bg_img%\'); background-size: cover; position: absolute; top: 0; left: 0; width: 100%;height: 100%; z-index: -1;"></div>';
        if(isset($coords["button"]))
        {
            //Button
            //echo '<button class="bexi_button" type="button" style="position:absolute;z-index:2;top:'.($coords["button"][1]*$yblock).'px;left:'.($coords["button"][0]*$xblock).'px;">Click Me!</button>';
            echo '<div  style="position:absolute;z-index:2;top:'.($coords["button"][1]*$yblock).'px;left:'.($coords["button"][0]*$xblock).'px;width:'.(1*$xblock).'px;height:'.(1*$yblock).'px;"><div id="'.uniqid().'" class="bexi_button btn '.$GLOBALS["buttonClass"].'" style="font-size:'.($yblock/4).'px !important;line-height:'.(0.85*($yblock/4)).'px !important;margin-top:2px !important;margin-bottom:2px !important;max-width:none;">Click Me!</div></div>';
        }

        if(isset($coords["text1"]))
        {
            //text1
            echo '<div  style="position:absolute;z-index:5;top:'.($coords["text1"][1]*$yblock).'px;left:'.($coords["text1"][0]*$xblock).'px;width:'.(3*$xblock).'px;height:'.(1*$yblock).'px;"><h1 class="bexi_title" style="color:white;font-size:'.($yblock/3).'px !important;line-height:'.(0.85*($yblock/3)).'px !important;">Text1</h1></div>';
        }

        if(isset($coords["text2"]))
        {
            //text2
            echo '<div  style="position:absolute;z-index:4;top:'.($coords["text2"][1]*$yblock).'px;left:'.($coords["text2"][0]*$xblock).'px;width:'.(2*$xblock).'px;height:'.(1*$yblock).'px;"><h3 class="bexi_subtitle" style="color:white;font-size:'.($yblock/4).'px !important;line-height:'.(0.85*($yblock/4)).'px !important;">Text2</h3></div>';
        }

        if(isset($coords["icon"]))
        {
            //icon
            echo '<div  style="position:absolute;z-index:3;top:'.($coords["icon"][1]*$yblock).'px;left:'.($coords["icon"][0]*$xblock).'px;width:'.(1*$xblock).'px;height:'.(1*$yblock).'px;"><i id="'.uniqid().'" class="fab fa-jedi-order bexi_icon" style="font-size:'.($yblock/2).'px;color:white;"></i></div>';

        }

        if(isset($coords["img"]))
        {
            //Img
            echo '<div  style="position:absolute;z-index:1;top:'.($coords["img"][1]*$yblock).'px;left:'.($coords["img"][0]*$xblock).'px;width:'.(3*$xblock).'px;height:'.(3*$yblock).'px;"><img id="'.uniqid().'" class="bexi_img m-0" src="%img|'.(3*$xblock).'|'.(3*$yblock).'|%" alt="img" style="max-width:100%;height:auto;max-height:100%;"></div>';
        }

        echo '</div>';

        echo "</body>";
        echo "\r\n";
        echo "</html>";
        $code = ob_get_contents ();

        ob_end_clean();

        $code=setImages($code,$_REQUEST["keywords"]);
        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' .$code);

            $tags = $doc->getElementsByTagName('img');
            foreach ($tags as $tag) {
                $src = $tag->getAttribute('src');
                if (stripos($src,"https://images.unsplash.com")!==false)
                {
                    $url = parse_url($src);
                    parse_str($url["query"],$result_array);
                    $result_array['q']=0;
                    $src = urldecode($url["scheme"]."://".$url["host"]."/".$url["path"]."?".http_build_query($result_array));
                    $tag->SetAttribute('src'.$src);
                }
            }

            $tags=$doc->getElementsByTagName('div');
            foreach ($tags as $tag) {
                $class = $tag->getAttribute('class');
                if (stripos($class,"transpa-bg")!==false)
                {
                    $style = $tag->getAttribute('style');
                    $src=get_string_between($style,'url(',');');
                    if (stripos($src,"https://images.unsplash.com")===0)
                    {
                        $url = parse_url($src);
                        parse_str($url["query"],$result_array);
                        $result_array['q']=0;
                        $src = urldecode($url["scheme"]."://".$url["host"]."/".$url["path"]."?".http_build_query($result_array));
                        $newstyle=set_string_between($style,'url(',');',$src);
                        $tag->SetAttribute('style',$newstyle);
                    }
                }
            }

        // dirty fix
        foreach ($doc->childNodes as $item)
        if ($item->nodeType == XML_PI_NODE)
            $doc->removeChild($item); // remove hack
        $doc->encoding = 'UTF-8'; // insert proper
        $code = $doc->saveHTML();

        /************** SAVE INTO DB ***************/

        $codeId = strval(microtime(true));

        $data = '{
           "id" : "'. $codeId .'",
           "user" : "'.$_REQUEST["user"].'",
           "code" : "'.base64_encode(gzcompress($code, 7)) .'",
           "coords" : ['.json_encode($coords).'],
           "xblock" : "'.$xblock.'",
           "yblock" : "'.$yblock.'"
        }';

        $item = $marshaler->marshalJson($data);

        $params = [
            'TableName' => 'bexi_projects_tmp',
            'Item' => $item
        ];

        $res["error"]="";
        $res["codeid"]=$codeId;
        try {
            $result = $dynamodb->putItem($params);
        } catch (DynamoDbException $e) {
            $res["error"] = $e->getMessage();
        }

        echo stripslashes(json_encode($res));
    }

    if($_REQUEST["cmd"]=="selector" && isset($_REQUEST["user"]) && isset($_REQUEST["codeid"]))
    {
        /********* GET HTML CODE FROM DB **********/
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
         $content =  gzuncompress(base64_decode($marshaler->unmarshalValue($result['Items'][0]["code"])));
         echo $content;
    }

    if($_REQUEST["cmd"]=="editor" && isset($_REQUEST["user"]) && isset($_REQUEST["codeid"]))
    {
        
    }

}
?>