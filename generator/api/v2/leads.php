<?php

function GetLeads($idcampaign, $pagesize)
{
	global $Dynamodb;
	global $Marshaler;

	$ret["error_code"] = "0";

	$LeadsKeys =$Marshaler->marshalJson('{
		":campaign" : "'.$idcampaign.'"
	}');

	$params = [
	    'TableName' => "modu_contacts",
	    "IndexName" => "campaign-index",
	    "KeyConditionExpression"=> "campaign = :campaign",
	    "ExpressionAttributeValues"=> $LeadsKeys , 
	    "limit" => $pagesize
	];

	print_r($params);

	$result = $Dynamodb->query($params);

	echo "results: ";
	print_r($result);

	//$table = ExecuteQuery("modu_contacts",$LeadsKeys,"campaign = :campaign", "" , false);
	$Leads = []; 


	//print_r($table);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		//print_r($dbdata);
		if (count($dbdata)>0)
		{
			$res["error"]=0;
			foreach ($table["data"]['Items'] as $dbRes) {
				$camp = []; 
				$camp["id"] = $Marshaler->unmarshalValue($dbRes["id"]);

				

				$camp["name"] = $Marshaler->unmarshalValue($dbRes["campaign_name"]);
				
				$projects [] = $camp;
			}
			$Leads=array_reverse($projects);
		}
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
	$res["data"]["rows"] = $Leads;
	//print_r($res);
	return  $res;
}

?>