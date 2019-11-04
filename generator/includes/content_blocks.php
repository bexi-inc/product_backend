
<?
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

?>
