<?
include "/var/www/generator.bexi.co/public_html/product_backend/generator/includes/global.php";
include "/var/www/generator.bexi.co/public_html/product_backend/generator/includes/utils.php";
include "/var/www/generator.bexi.co/public_html/product_backend/generator/config.php";
include "/var/www/generator.bexi.co/public_html/product_backend/generator/vendor/autoload.php";
require_once "/var/www/generator.bexi.co/public_html/product_backend/generator/includes/JSLikeHTMLElement.php";

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

    if($_REQUEST["cmd"]=="CreateAd" && isset($_REQUEST["user"])  && isset($_REQUEST["headline"])  && isset($_REQUEST["cta"]) ){

        /***********Get recipe from DB **********/
        $recipe="--Service";
        /**************** Search AdLayout with recipe elements ******************/
        $params = [
            'TableName' => "modu_ads_blocks",
            "FilterExpression" => "contains(#descr, :v)",
            "ExpressionAttributeValues"=> [
                ":v" =>  ["S" => $recipe]
                ],
            "ExpressionAttributeNames" =>
                [ '#descr' => 'description']
        ];
        $result = $dynamodb->scan($params);
        /************************** Shuffle and select 1 random *********************/
        $max=$result['Count'];
        $random=rand(0,$max-1);
        $content=$marshaler->unmarshalValue($result['Items'][$random]["html_code"]);

        /******************** Remplace IDs *******************/
        $pos = 0;
        $pos2 = 0;
        while ( ( $pos = strpos( $content, "%id%", $pos ) ) !== false ) {
          $pos2 = strpos( $content, "%", ($pos + 1) );
          $idrand = uniqid('bexi_');
          $content=substr_replace($content,$idrand,$pos,4);
        }

        ob_start();
        echo $content;

        $code = ob_get_contents ();

        ob_end_clean();
        /****************** get unsplash imgs **************/
        $code=setImages($code,$_REQUEST["keywords"]);
        $doc = new DOMDocument();
        $doc->registerNodeClass('DOMElement', 'JSLikeHTMLElement');
        $doc->loadHTML('<?xml encoding="UTF-8">' .$code);
        /********************* Add css depend of theme *****************/
        //get head element
        $head = $doc->getElementsByTagName('head');

        //create style element
        $elementStyle1 = $doc->createElement('link', '');
        $elementStyle1->setAttribute('rel', urldecode('stylesheet'));
        $elementStyle1->setAttribute('href', urldecode('http://generator.bexi.ai/css/bexi.css'));
        $elementStyle1->setAttribute('data-css', "default");
        $elementStyle2 = $doc->createElement('link', '');
        $elementStyle2->setAttribute('rel', urldecode('stylesheet'));
        $elementStyle2->setAttribute('href', urldecode('http://generator.bexi.ai/css/bexi_ad.css'));
        $elementStyle2->setAttribute('data-css', "default-ad");

        /**************   AUTOTEX **************/
        $elementScript1 = $doc->createElement('script', '');
        $elementScript1->setAttribute('type', urldecode('text/javascript'));
        $elementScript1->setAttribute('src', urldecode('http://generator.bexi.ai/includes/jquery.textfill.js'));
        $elementScript1->setAttribute('class', "text");
        $elementScript2 = $doc->createElement('script', '
        $(document).ready(function() {
            $(".bexi_button").wrapInner("<div class=\'text-aux\' style=\'line-height:0.9em;padding:25px;\'></div>");
            $(".bexi_title").wrapInner("<div class=\'text-aux\' style=\'line-height:0.9em;\'></div>");
            $(".bexi_title").textfill({
                maxFontPixels: 64,
                changeLineHeight: false,
                innerTag: "div"
              });
              $(".bexi_button").textfill({
                maxFontPixels: 30,
                changeLineHeight: false,
                innerTag: "div"
              });
        });
        ');
        $elementScript2->setAttribute('type', urldecode('text/javascript'));
        $elementScript2->setAttribute('class', "text");

        //add style and script elements
        $head[0]->appendChild($elementStyle1);
        $head[0]->appendChild($elementStyle2);
        $head[0]->appendChild($elementScript1);
        $head[0]->appendChild($elementScript2);
        /************************** Reemplace inner text  ***************/
        $tags = $doc->getElementsByTagName('div');
        foreach ($tags as $tag) {
            $src = $tag->getAttribute('class');
            if (stripos($src,"headline")!==false)
            {
                $tag->innerHTML=$_REQUEST["headline"];
            }

            if (stripos($src,"cta")!==false)
            {
                $tag->innerHTML=$_REQUEST["cta"];
            }
        }



        /****************** Change unsplash imgs quality **************/
            $tags = $doc->getElementsByTagName('img');
            foreach ($tags as $tag) {
                $src = $tag->getAttribute('src');
                if (stripos($src,"https://images.unsplash.com")!==false)
                {
                    $url = parse_url($src);
                    parse_str($url["query"],$result_array);
                    $result_array['q']=0;
                    $src = urldecode($url["scheme"]."://".$url["host"].$url["path"]."?".http_build_query($result_array));
                    $tag->SetAttribute('src',$src);
                }
            }

            $tags=$doc->getElementsByTagName('div');
            foreach ($tags as $tag) {
                $class = $tag->getAttribute('class');
                if (stripos($class,"transpa-bg")!==false)
                {
                    $style = $tag->getAttribute('style');
                    $src=get_string_between($style,"url('","');");
                    if (stripos($src,"https://images.unsplash.com")!==false)
                    {
                        $url = parse_url($src);
                        parse_str($url["query"],$result_array);
                        $result_array['q']=0;
                        $src = urldecode($url["scheme"]."://".$url["host"].$url["path"]."?".http_build_query($result_array));
                        $newstyle=set_string_between($style,"url('","');",$src);
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
           "code" : "'.base64_encode(gzcompress($code, 7)) .'"
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

    if($_REQUEST["cmd"]=="editor" && isset($_REQUEST["user"]) && isset($_REQUEST["codeid"]) && isset($_REQUEST["codename"]) && isset($_REQUEST["create"]))
    {
        /********* GET HTML CODE FROM DB **********/
        $params = [
            'TableName' => "modu_ads_service",
                "KeyConditionExpression"=> "id = :id",
            "ExpressionAttributeValues"=> [
                ":id" =>  ["S" => $_REQUEST["codeid"]]
            ]
        ];

        $result = $dynamodb->query($params);


        if($result['Count']>0)
        {
            $content =  gzuncompress(base64_decode($marshaler->unmarshalValue($result['Items'][0]["code"])));
        }
        else{
            /********* GET HTML CODE FROM temporal DB **********/
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

            $code = $marshaler->unmarshalValue($result['Items'][0]["code"]);

            $content =  gzuncompress(base64_decode($marshaler->unmarshalValue($result['Items'][0]["code"])));

            /************** SAVE INTO DB ***************/

            $data = '{
            "id" : "'. $_REQUEST["codeid"] .'",
            "codename" : "'.$_REQUEST["codename"].'",
            "code" : "'. $code .'"
            }';

            $item = $marshaler->marshalJson($data);

            $params = [
                'TableName' => 'modu_ads_service',
                'Item' => $item
            ];

            $res["error"]="";
            $res["codeid"]=$codeId;
            try {
                $result = $dynamodb->putItem($params);
            } catch (DynamoDbException $e) {
                $res["error"] = $e->getMessage();
            }
        }

        $dom=new domDocument;
		libxml_use_internal_errors(true);
		$dom->loadHTML($content);
		libxml_use_internal_errors(false);
        $dom->preserveWhiteSpace = false;


        /****************** Change unsplash imgs quality **************/
        $tags = $dom->getElementsByTagName('img');
        foreach ($tags as $tag) {
            $src = $tag->getAttribute('src');
            if (stripos($src,"https://images.unsplash.com")!==false)
            {
                $url = parse_url($src);
                parse_str($url["query"],$result_array);
                $result_array['q']=100;
                $src = urldecode($url["scheme"]."://".$url["host"].$url["path"]."?".http_build_query($result_array));
                $tag->SetAttribute('src',$src);
            }
        }

        $tags=$dom->getElementsByTagName('div');
        foreach ($tags as $tag) {
            $class = $tag->getAttribute('class');
            if (stripos($class,"transpa-bg")!==false)
            {
                $style = $tag->getAttribute('style');
                $src=get_string_between($style,"url('","');");
                if (stripos($src,"https://images.unsplash.com")!==false)
                {
                    $url = parse_url($src);
                    parse_str($url["query"],$result_array);
                    $result_array['q']=100;
                    $src = urldecode($url["scheme"]."://".$url["host"].$url["path"]."?".http_build_query($result_array));
                    $newstyle=set_string_between($style,"url('","');",$src);
                    $tag->SetAttribute('style',$newstyle);
                }
            }
        }

        /*********************** DELETE ADITIONAL AUTOTEXT SCRIPTS ********************/
        $tags=$dom->getElementsByTagName('script');
        foreach ($tags as $tag) {
            $class = $tag->getAttribute('class');
            if (stripos($class,"text")!==false)
            {
                $tag->parentNode->removeChild($tag);
            }
        }

        //get head element
        $head = $dom->getElementsByTagName('head');

        //create style element
        $elementStyle = $dom->createElement('style', '.bexi_module_ad{top: 39px !important;}');
        $elementStyle->setAttribute('data-editor', "true");
        //create script src element
        $elementScript1 = $dom->createElement('script', '');
        $elementScript1->setAttribute('type', urldecode('text/javascript'));
        $elementScript1->setAttribute('src', urldecode('ad_editor-service.js'));
        $elementScript1->setAttribute('data-editor', "true");
        $elementScript2 = $dom->createElement('script', 'var FroalaKey = "'.FROALA_KEY.'";');
        $elementScript2->setAttribute('type', urldecode('text/javascript'));
        $elementScript2->setAttribute('data-editor', "true");
        /**************   Jquery dialog **************/
        $elementScript3 = $dom->createElement('script', '');
        $elementScript3->setAttribute('type', urldecode('text/javascript'));
        $elementScript3->setAttribute('src', urldecode('http://generator.bexi.ai/includes/jquery-ui.min.js'));
        $elementScript3->setAttribute('data-editor', "true");
        $elementStyle2 = $dom->createElement('link', '');
        $elementStyle2->setAttribute('rel', urldecode('stylesheet'));
        $elementStyle2->setAttribute('href', urldecode('http://generator.bexi.ai/includes/jquery-ui.min.css'));
        $elementStyle2->setAttribute('data-editor', "true");
        $elementStyle3 = $dom->createElement('link', '');
        $elementStyle3->setAttribute('rel', urldecode('stylesheet'));
        $elementStyle3->setAttribute('href', urldecode('http://generator.bexi.ai/includes/jquery-ui.theme.css'));
        $elementStyle3->setAttribute('data-editor', "true");
        /**************   Jquery palett colors **************/
        $elementScript4 = $dom->createElement('script', '');
        $elementScript4->setAttribute('type', urldecode('text/javascript'));
        $elementScript4->setAttribute('src', urldecode('http://generator.bexi.ai/includes/jquery.minicolors.min.js'));
        $elementScript4->setAttribute('data-editor', "true");
        $elementStyle4 = $dom->createElement('link', '');
        $elementStyle4->setAttribute('rel', urldecode('stylesheet'));
        $elementStyle4->setAttribute('href', urldecode('http://generator.bexi.ai/includes/css/jquery.minicolors.css'));
        $elementStyle4->setAttribute('data-editor', "true");
        /**************   PAGINATION **************/
        $elementScript5 = $dom->createElement('script', '');
        $elementScript5->setAttribute('type', urldecode('text/javascript'));
        $elementScript5->setAttribute('src', urldecode('https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js'));
        $elementScript5->setAttribute('data-editor', "true");
        $elementScript6 = $dom->createElement('script', '');
        $elementScript6->setAttribute('type', urldecode('text/javascript'));
        $elementScript6->setAttribute('src', urldecode('http://generator.bexi.ai/includes/pagination.js'));
        $elementScript6->setAttribute('data-editor', "true");
        $elementStyle5 = $dom->createElement('link', '');
        $elementStyle5->setAttribute('rel', urldecode('stylesheet'));
        $elementStyle5->setAttribute('href', urldecode('http://generator.bexi.ai/includes/css/bs-pagination.css'));
        $elementStyle5->setAttribute('data-editor', "true");
        /**************   FROALA EDITOR **************/
        $elementScript7 = $dom->createElement('script', '');
        $elementScript7->setAttribute('type', urldecode('text/javascript'));
        $elementScript7->setAttribute('src', urldecode('https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/js/froala_editor.pkgd.min.js'));
        $elementScript7->setAttribute('data-editor', "true");
        $elementScript8 = $dom->createElement('script', '');
        $elementScript8->setAttribute('type', urldecode('text/javascript'));
        $elementScript8->setAttribute('src', urldecode('https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/js/third_party/font_awesome.min.js'));
        $elementScript8->setAttribute('data-editor', "true");
        $elementStyle6 = $dom->createElement('link', '');
        $elementStyle6->setAttribute('rel', urldecode('stylesheet'));
        $elementStyle6->setAttribute('href', urldecode('https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/css/froala_editor.pkgd.min.css'));
        $elementStyle6->setAttribute('data-editor', "true");
        $elementStyle7 = $dom->createElement('link', '');
        $elementStyle7->setAttribute('rel', urldecode('stylesheet'));
        $elementStyle7->setAttribute('href', urldecode('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css'));
        $elementStyle7->setAttribute('data-editor', "true");
        /**************   CANVAS **************/
        $elementScript9 = $dom->createElement('script', '');
        $elementScript9->setAttribute('type', urldecode('text/javascript'));
        //$elementScript9->setAttribute('src', urldecode('https://github.com/niklasvh/html2canvas/releases/download/v1.0.0-alpha.4/html2canvas.js'));
        $elementScript9->setAttribute('src', urldecode('https://github.com/niklasvh/html2canvas/releases/download/v1.0.0-rc.5/html2canvas.js'));
        $elementScript9->setAttribute('data-editor', "true");
        /**************   AUTOTEX **************/
        $elementScript10 = $dom->createElement('script', '');
        $elementScript10->setAttribute('type', urldecode('text/javascript'));
        //$elementScript10->setAttribute('src', urldecode('http://generator.bexi.ai/includes/jquery.bb-textfitter.js'));
        $elementScript10->setAttribute('src', urldecode('http://generator.bexi.ai/includes/jquery.textfill.js'));
        //$elementScript10->setAttribute('src', urldecode('http://generator.bexi.ai/includes/fitty.min.js'));
        $elementScript10->setAttribute('data-editor', "true");

        //add style and script elements
        $head[0]->appendChild($elementStyle);
        $head[0]->appendChild($elementStyle2);
        $head[0]->appendChild($elementStyle3);
        $head[0]->appendChild($elementStyle4);
        $head[0]->appendChild($elementStyle5);
        $head[0]->appendChild($elementStyle6);
        $head[0]->appendChild($elementStyle7);
        $head[0]->appendChild($elementScript1);
        $head[0]->appendChild($elementScript2);
        $head[0]->appendChild($elementScript3);
        $head[0]->appendChild($elementScript4);
        $head[0]->appendChild($elementScript5);
        $head[0]->appendChild($elementScript6);
        $head[0]->appendChild($elementScript7);
        $head[0]->appendChild($elementScript8);
        $head[0]->appendChild($elementScript9);
        $head[0]->appendChild($elementScript10);

        //get body element
        $body = $dom->getElementsByTagName('body');
        //create input element
        $input = $dom->createElement('input', '');
        $input->setAttribute('id', "codeId");
        $input->setAttribute('value',$_REQUEST["codeid"]);
        $input->setAttribute('data-editor', "true");
        $input->setAttribute('type', "hidden");
        $body[0]->appendChild($input);

        echo $dom->savehtml();
    }

    if($_REQUEST["cmd"]=="getprojects"){

        $params = [
            'TableName' => "modu_ads_service",
        ];
        $table = $dynamodb->scan($params);
        if ($table["error"]=="")
        {
            $dbdata = $table['Items'];
            if (count($dbdata)>0)
            {
                $projects=[];
                foreach ($dbdata as $item) {
                    $parttemp = [];//temporaly part with the values converted
                    $parttemp["id"] = $marshaler->unmarshalValue($item["id"]);
                    $parttemp["code"]=$marshaler->unmarshalValue($item["codename"]);
                    $projects [] = $parttemp;//add the part to the array
                }
                $ret["error_code"] = "0";
                $ret["contents"] = $projects;
                echo stripslashes(json_encode($ret));
            }
        }
        else{
            $ret["error_code"] = "500";
            $ret["message"] =  $table["error"];
            echo stripslashes(json_encode($ret));
        }
    }

    if($_REQUEST["cmd"]=="autosave" && isset($_REQUEST["codeid"]) && isset($_REQUEST["code"]))
    {
        $key = '
        {
            "id" : "'.$_REQUEST["codeid"].'"
        }
        ';
        $key = $marshaler->marshalJson($key);

        $updateData='{
            ":code" : "'.base64_encode(gzcompress($_REQUEST["code"], 7)) .'"
        }';

        $eav = $marshaler->marshalJson($updateData);

        $params = [
            'TableName' => "modu_ads_service",
            'Key' => $key,
            'UpdateExpression' => 'set code = :code',
            'ExpressionAttributeValues'=> $eav,
            'ReturnValues' => 'UPDATED_NEW'
        ];

        try {
            $result = $dynamodb->updateItem($params);
            $res["error"] = 0;
        }
        catch (DynamoDbException $e){
            $res["error"] = 500;
            $res["error_msj"] = "Unable to update";
        }

        echo json_encode($res);
    }

    if($_REQUEST["cmd"]=="test")
    {
                /********* GET HTML CODE FROM DB **********/
        $params = [
            'TableName' => "bexi_projects_tmp",
             "KeyConditionExpression"=> "id = :id AND #usr=:usr",
            "ExpressionAttributeValues"=> [
                ":id" =>  ["S" => "1584386720.4259"],
                ":usr" => ["S" => "-1"]
            ],
            "ExpressionAttributeNames" =>
                [ '#usr' => 'user' ]
        ];

        $result = $dynamodb->query($params);
        $content =  gzuncompress(base64_decode($marshaler->unmarshalValue($result['Items'][0]["code"])));
        //echo $content;

        $dom=new domDocument;
		libxml_use_internal_errors(true);
		$dom->loadHTML($content);
		libxml_use_internal_errors(false);
        $dom->preserveWhiteSpace = false;

        //get head element
        $head = $dom->getElementsByTagName('head');

        //create style element
        $elementStyle = $dom->createElement('style', '.bexi_module_ad{top: 39px !important;}');

        // //create script src element
        // $elementScript = $dom->createElement('script', '');
        // $elementScript->setAttribute('type', urldecode('text/javascript'));
        // $elementScript->setAttribute('src', urldecode('includes/ad_editor.js'));

        //create script src element
        $elementScript2 = $dom->createElement('script', '');
        $elementScript2->setAttribute('type', urldecode('text/javascript'));
        //$elementScript2->setAttribute('src', urldecode('includes/domtoimg.js'));
        //$elementScript2->setAttribute('src', urldecode('https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js'));
        $elementScript2->setAttribute('src', urldecode('https://github.com/niklasvh/html2canvas/releases/download/v1.0.0-alpha.4/html2canvas.js'));
        //$elementScript2->setAttribute('src', urldecode('includes/html2canvas.js'));
        
        //create script src element
        $elementScript3 = $dom->createElement('script', '');
        $elementScript3->setAttribute('type', urldecode('text/javascript'));
        $elementScript3->setAttribute('src', urldecode('includes/imgbutton.js'));

        //add style and script elements
        $head[0]->appendChild($elementStyle);
        //$head[0]->appendChild($elementScript);
        $head[0]->appendChild($elementScript2);
        $head[0]->appendChild($elementScript3);

        $tags = $dom->getElementsByTagName('img');
        foreach ($tags as $tag) {
            $src = $tag->getAttribute('src');
            if (stripos($src,"https://images.unsplash.com")!==false)
            {
                $url = parse_url($src);
                parse_str($url["query"],$result_array);
                $result_array['q']=1;
                $src = urldecode($url["scheme"]."://".$url["host"].$url["path"]."?".http_build_query($result_array));
                $tag->SetAttribute('src',$src);
            }
        }

        $tags=$dom->getElementsByTagName('div');
        foreach ($tags as $tag) {
            $class = $tag->getAttribute('class');
            if (stripos($class,"transpa-bg")!==false)
            {
                $style = $tag->getAttribute('style');
                $src=get_string_between($style,"url('","');");
                if (stripos($src,"https://images.unsplash.com")!==false)
                {
                    $url = parse_url($src);
                    parse_str($url["query"],$result_array);
                    $result_array['q']=1;
                    $src = urldecode($url["scheme"]."://".$url["host"].$url["path"]."?".http_build_query($result_array));
                    $newstyle=set_string_between($style,"url('","');",$src);
                    $tag->SetAttribute('style',$newstyle);
                }
            }
        }

        echo $dom->savehtml();
    }

}
?>