<?

include "./api/v1/db.php";

$cmd = $_REQUEST["cmd"];

function GetIdContents()
{
	global $Marshaler;
	
	$table = scanAll("bexi_prod_contentblock");

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
            $contents=[];
			$res["error"]="0";
			$res["message"] = "Deliverable found";
            foreach ($dbdata as $item) {
				$parttemp = [];//temporaly part with the values converted
				$parttemp["id"] = $Marshaler->unmarshalValue($item["id"]);
				$parttemp["code"]=$Marshaler->unmarshalValue($item["code"]);
                $parttemp["type"]=$Marshaler->unmarshalValue($item["type"]);
				$contents [] = $parttemp;//add the part to the array
            }
            
            $ret["error_code"] = "0";
            $ret["contents"] = $contents;
	        return $ret;
		}
	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
	return $res;
}

switch ($cmd) {
	case "blocks":
		   $res = GetIdContents();
    break;
}

 echo json_encode($res);
?>

