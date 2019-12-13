
<?

function create_recipe($db, $coder,$proj_id)
{
    $type=1;//get type of recipe
	$ret["error_code"] = "0";


	$eav = $coder->marshalJson('
		{
			":id": "'.$type.'" 
		}
	');

	$params = [
		'TableName' => "modu_recipes_lp",
		"KeyConditionExpression"=> "#id = :id",
		"ExpressionAttributeValues"=> [
			":id" =>  ["S" => $idParam]
		],
		"ExpressionAttributeNames" =>   
			[ '#id' => 'id' ]
		
	];


 	$table = $db->query($params);
	$parts = []; //array to save the parts
	$final=[];
	//print_r($table);

	if ($table["error"]=="")
	{
		$dbdata = $coder->unmarshalValue($table["data"]['Items'][0]["part"]);
		//print_r($table["data"]['Items'][0]["part"]["M"][0]);
		if (count($dbdata)>0)
		{
			$res["error"]=0;
			foreach ($dbdata as $key => $value) {
				$parttemp = [];//temporaly part with the values converted
				$parttemp["number"] = $key;//get the number
				$parttemp["contents"]=$value;
				$parts [] = $parttemp;//add the part to the array
			}
			/********* Add espaces in missing parts **********/
			for ($i=0;$i <= 7; $i++) {
				if($parts[$i]["number"]!==$i)
				{
					$parttemp = [];
					$parttemp["number"] = -1;
					$parttemp["contents"];
					array_splice( $parts, $i, 0, $parttemp);
				}
			}
			/*********vary order between 2-3**********/
			$temparray=[];
			$temparray=array_slice($parts, 1,2);//copy part 2,3
			shuffle($temparray);
			array_splice( $parts,1,2,$temparray);
			/*********vary order between 4-7**********/
			$temparray=array_slice($parts, 3,4);//copy part 4,5,6,7
			shuffle($temparray);
			array_splice( $parts,3,4,$temparray);
			/********* remove espaces in missing parts **********/
			$newparts=[];
			for ($i=0;$i <= 7; $i++) {
				if($parts[$i]["number"]!==-1)
				{
					$newparts[]=$parts[$i];
				}
			}
			//random pickup contents for each part
			foreach ($newparts as $part) {
				$final [] =  $part["contents"][array_rand($part["contents"], 1)];//add the id-content random to the array
			}
		}
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
	$res["data"] = $final;
	//print_r($res);
	return  $res;
}

function GethtmlCode($db, $coder, $idParam)
{

	$eav = $coder->marshalJson('
    	{
        	":id": "'.$idParam.'" 
    	}
	');

	$params = [
	    'TableName' => "bexi_prod_contentblock",
	     "KeyConditionExpression"=> "#id = :id",
	    "ExpressionAttributeValues"=> [
	        ":id" =>  ["S" => $idParam]
	    ],
	    "ExpressionAttributeNames" =>   
	        [ '#id' => 'id' ]
	    
	];


 	$result = $db->query($params);
 	return $coder->unmarshalValue($result['Items'][0]['html_code']);
}


function CreateProject($marshaler, $dynamodb, $UserId, $Keywords="")
{ 
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
			
			/****************************************************************mi codigo ************************/
			$recipe=create_recipe($dynamodb,  $marshaler,123);
			$recipe=$recipe["data"];
			for ($i = 1; $i <= count($recipe); $i++) {
				$ContenidoTmp=setImages(GethtmlCode($dynamodb,$marshaler,$recipe[$i]),$Keywords);
				$contenido .= substr_replace($ContenidoTmp," id='module_".$i."' ",4,0);
				//$css[]=$marshaler->unmarshalValue($result['Items'][$key]["file_css"]);

				$_SESSION["modules"][]=$recipe[$i];
			}
			

			/***************************************************************************************************alv de manuel *************************************/

			// //echo "Tiempo 6 : ".(microtime(true) - $timeini)."<br>";

		    // $params = [
		    //     'TableName' => "bexi_prod_contentblock",
		    //     "IndexName" => "type-index",
		    //     'ProjectionExpression' => 'id, #cod, is_content, #tp, file_css, file_html',
		    //      "KeyConditionExpression"=> "#tp = :vtipo",
		    //     "ExpressionAttributeValues"=> [
		    //         ":vtipo" =>  ["S" => "int"]
		    //     ],
		    //     "ExpressionAttributeNames" =>   
		    //         [ '#tp' => 'type',
		    //             '#cod' => 'code' ]

		    // ];

		    // //$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

		    // //$contenido="";

		    //  $result = $dynamodb->query($params);

		    //  //echo "Tiempo 7 : ".(microtime(true) - $timeini)."<br>";

		    // $key = array_rand ($result['Items'],1);

		    // $ContenidoTmp=setImages(GethtmlCode($dynamodb,$marshaler,$marshaler->unmarshalValue($result['Items'][$key]['id'])),$Keywords);
		    // $contenido .= substr_replace($ContenidoTmp," id='module_".$i."' ",4,0);
		    // $css[]=$marshaler->unmarshalValue($result['Items'][$key]["file_css"]);

		    // $_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);

		    // //echo "Tiempo 8 : ".(microtime(true) - $timeini)."<br>";
		            
		    // /******************************************************
		    // ************    CALCULAMOS EL CONTENIDO ***************
		    // ******************************************************/

		    // $nmods=rand(1,12);
		    // $modulos ="";

		    // $params = [
		    //     'TableName' => "bexi_prod_contentblock",
		    //     "FilterExpression" => "#tp <> :vtipo1 AND #tp<>:vtipo2 AND #tp<>:vtipo3",
		    //     'ProjectionExpression' => 'id, #cod, is_content, #tp, file_css, file_html',
		    //     "ExpressionAttributeValues"=> [
		    //         ":vtipo1" =>  ["S" => "foo"],
		    //         ":vtipo2" =>  ["S" => "hea"],
		    //         ":vtipo3" =>  ["S" => "int"]
		    //         ],
		    //     "ExpressionAttributeNames" =>   
		    //         [ '#tp' => 'type',
		    //           '#cod' => 'code' ]
		    //     ];

		    //     $result = $dynamodb->scan($params);

		    // for ($i = 1; $i <= $nmods; $i++) {

		        

		    //   //  echo "Tiempo 9 : ".(microtime(true) - $timeini)."<br>";

		    //     $key = array_rand ($result['Items'],1);

		    //     $ContenidoTmp=setImages(GethtmlCode($dynamodb,$marshaler,$marshaler->unmarshalValue($result['Items'][$key]['id'])),$Keywords);
		    //     $contenido .= substr_replace($ContenidoTmp," id='module_".$i."' ",4,0);
		        

		    //     $css[]=$marshaler->unmarshalValue($result['Items'][$key]["file_css"]);

		    //     $_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);
		    //     //echo "Tiempo 10 : ".(microtime(true) - $timeini)."<br>";
		        
		    //     unset($result['Items'][$key]);     
		    // }

		    // //echo "Tiempo 11 : ".(microtime(true) - $timeini)."<br>";
		    // $params = [
		    //     'TableName' => "bexi_prod_contentblock",
		    //     "IndexName" => "type-index",
		    //      'ProjectionExpression' => 'id, #cod, is_content, #tp, file_css, file_html',
		    //      "KeyConditionExpression"=> "#tp = :vtype",
		    //     "ExpressionAttributeValues"=> [
		    //         ":vtype" =>  ["S" => "foo"]
		    //     ],
		    //     "ExpressionAttributeNames" =>   
		    //         [ '#tp' => 'type',
		    //           '#cod' => 'code' ]
		    //     ];


		    //  $set_colors = $dynamodb->query($params);

		    // $key = array_rand ($set_colors['Items'],1);
		    // $contenido.=GethtmlCode($dynamodb,$marshaler,$marshaler->unmarshalValue($set_colors['Items'][$key]['id']));
			// $css[]=$marshaler->unmarshalValue($set_colors['Items'][$key]["file_css"]);

/***************************************************************************************************alv de manuel *************************************/
		    //$_SESSION["modules"][]=$marshaler->unmarshalValue($result['Items'][$key]["id"]);


		    // OBTENEMOS EL FONT POR RANDON
/*
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
*/
		    //echo "Time Final:".(microtime(true) - $timeini)."<br>"; 


		    $pos = 0;
		    $pos2 = 0;
		    while ( ( $pos = strpos( $contenido, "%id%", $pos ) ) !== false ) {
		      $pos2 = strpos( $contenido, "%", ($pos + 1) );
		      $idrand = uniqid('bexi_');
		      $contenido=substr_replace($contenido,$idrand,$pos,4);
		    }

		    $codeId = strval(microtime(true));

		     $data1 = '{
	            "id" : "'. $codeId .'",
	            "user" : "'.$UserId.'",
	            "code" : "'.base64_encode(gzcompress($contenido, 7)) .'"
	        }';

	        //$data1 = json_encode($data1);
	        //print_r($data1);
	        $item = $marshaler->marshalJson($data1);

	        $params = [
	            'TableName' => 'bexi_projects_tmp',
	            'Item' => $item
	        ];

	        $res["error"]="";
	        $res["codeid"]=$codeId;
	        $res["userid"] = $UserId;
	        try {
	            $result = $dynamodb->putItem($params);
	            //return true;
	        } catch (DynamoDbException $e) {
	            $res["error"] = $e->getMessage();
	        }
		    //print_r($contenido);\

		    return $res;
}

?>
