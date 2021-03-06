<?php

function GetLeads($idcampaign, $pagesize, $last_key)
{
	global $Dynamodb;
	global $Marshaler;

	/***********************
	REMOVE ON PRODUCTION 
	**************************/
	$idcampaign=1;

	$back_key = $last_key;


	$ret["error_code"] = "0";

	$LeadsKeys =$Marshaler->marshalJson('{
		":campaign" : "'.$idcampaign.'"
	}');

	$params = [
	    'TableName' => "modu_contacts",
	    "IndexName" => "campaign-index",
	    "KeyConditionExpression"=> "campaign = :campaign",
	    "ExpressionAttributeValues"=> $LeadsKeys , 
	    "ProjectionExpression" => "id , campaign"
	];

	

	$result = $Dynamodb->query($params);

	//echo "Count";
	//print_r($result);

	$TotalLeads = $result["Count"];

	$params = [
	    'TableName' => "modu_contacts",
	    "IndexName" => "campaign-index",
	    "KeyConditionExpression"=> "campaign = :campaign",
	    "ExpressionAttributeValues"=> $LeadsKeys , 
	    "Limit" => $pagesize
	];

	if (!empty($last_key))
	{
		$params["ExclusiveStartKey"] = $last_key;
	}

	//print_r($params);

	$result = $Dynamodb->query($params);

	//echo "results: ";
	//print_r($result);

	//$table = ExecuteQuery("modu_contacts",$LeadsKeys,"campaign = :campaign", "" , false);
	$Leads = []; 


	//print_r($result);

	if (!empty($result))
	{
		//$dbdata = $table["data"]['Items'];
		//print_r($dbdata);
		$last_key = $result['LastEvaluatedKey'];
		if (count($result['Items'])>0)
		{
			$res["error"]=0;
			foreach ($result['Items'] as $dbRes) {
				/*$camp = []; 
				$camp["id"] = $Marshaler->unmarshalValue($dbRes["id"]);

				

				$camp["name"] = $Marshaler->unmarshalValue($dbRes["campaign_name"]);*/
				
				$Leads [] = $Marshaler->unmarshalJson($dbRes);

			}
		}
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
	$res["data"]["total"] = $TotalLeads;
	$res["data"]["pages"] = ceil($TotalLeads / $pagesize);
	$res["data"]["last_key"] = $last_key;
	$res["data"]["back_key"] = $back_key;
	$res["data"]["rows"] = $Leads;
	//print_r($res);
	return  $res;
}

?>