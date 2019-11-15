<?

function GetStatusStr($status)
{
	switch($status){
		case 0:
			return "Draft";
			break;
		case 1:
			return "Unpublish";
			break;
		case 2:
			return "Publish";
			break;
		case 3:
			return "Modified";
			break;
		default:
			break;
	}
}

function GetTypeStr($Type)
{
	switch($status){
		case 1:
			return "Landing Page";
			break;
		default:
			return "Undefine";
			break;
	}
}

function GetProjects($connDyn, $userId)
{
	global $Marshaler;
	$ret["error_code"] = "0";

	$userData ='{
		":userid" : "'.$userId.'"
	}';

	$table = ExecuteQuery("modu_projects",$userData,"user_id = :userid", "user_id-index" , "" , false);
	$projects = []; 


	//print_r($table);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
			$res["error"]=0;
			foreach ($table["data"]['Items'] as $project) {
				$proj = []; 
				$proj["project _id"] = $Marshaler->unmarshalValue($project["project _id"]);
				$proj["project_name"] = $Marshaler->unmarshalValue($project["project_name"]);
				$proj["status"] = GetStatusStr($Marshaler->unmarshalValue($project["status"]));
				$proj["industry"] = $Marshaler->unmarshalValue($project["industry"]);
				$proj["type"] = "Landing Page";//GetTypeStr($Marshaler->unmarshalValue($project["type"]));

				$micro_date = date($Marshaler->unmarshalValue($project["create_date"]));
				$date_array = explode(".",$micro_date);
				$date = date("Y-m-d",$date_array[0]);
				$proj["create_date"] = $date;

				//echo date('Y-m-d H:i:s', $proj["date_create"]);
				$projects [] = $proj;
			}
		}
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
	$res["data"] = $projects;
	//print_r($res);
	return  $res;

}


function CreateProject($connDyn, $userid, $pname, $pgoal, $industry, $colors, $txtcolors,  $pkeywords , $pservices)
{
	global $Marshaler;
	$pid = microtime(true);
	$Data ='{
		"project_id" : "'.$pid.'"
		,"user_id" : "'.$userid.'"
		,"create_date" : "'.$pid.'"
		,"project_name" : "'.$pname.'"
		,"industry" : "'.$industry.'" ';

	$Data .= ((trim($pgoal) != '' ) ? ',"project_goal" :  "'.$pgoal.'" ' : '' );
		
	$Data .= ' ,"status" : "0"
		,"colors" : [
				'.$colors.'
		]
		,"txtcolors" : [
				'.$txtcolors.'
		]
		,"keywords" : "'.$pkeywords.'"
		,"pservices" : "'.$pservices.'"
	';
	$Data = $Data . '}';


	//print_r($Data);

	$resIns=Insert("modu_projects",$Data, false);

	if (!$resIns["error"])
	{
		$res["error"]="";
		$res["id"] =  $pid;


		if (!file_exists(PAHTSERVER.$userid)) {
		    mkdir($path.$userid, 0777, true);
		}

		if (!file_exists(PAHTSERVERth.$userid."/".$pid)) {
		    mkdir($path.$userid."/".$pid, 0777, true);
		}

		$fullpath= PAHTSERVER.$userid."/".$pid . "/";

		if (!file_exists($fullpath."/logos")) {
		    mkdir($fullpath."/logos", 0777, true);
		}

		$fullpath .= "/logos/";

		
		/*$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		$target_file = $fullpath."/".$idfile.".".$imageFileType;*/
		//$webpath = PATHWEB.$userid."/".$projectid . "/logos/".$_FILES["logofullcolor"]["name"];
		//print_r($_FILES);

		$target_file = $fullpath . $_FILES["logofullcolor"]["name"];

		if (move_uploaded_file($_FILES["logofullcolor"]["tmp_name"], $target_file)) {

		}

		$target_file = $fullpath . $_FILES["logodarker"]["name"];

		if (move_uploaded_file($_FILES["logodarker"]["tmp_name"], $target_file)) {

		}

		$target_file = $fullpath . $_FILES["logolighter"]["name"];

		if (move_uploaded_file($_FILES["logolighter"]["tmp_name"], $target_file)) {

		}

		return $res;
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $resIns["error"];
	    return $ret;
	}
}

function ExistDomain($domain)
{
	$data='
	    {
	        ":sdom": "'.$domain.'"
	    }
	';

	$table = ExecuteQuery("modu_subdomains",$data,"subdomain = :sdom","x","",false);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
		    if ($Marshaler->unmarshalValue($dbdata[0]['subdomain'])==$domain)
		    {
		    	$ret["error_code"] = "0";
		    	$ret["exists"] = "true";
		    	return $ret;
		    }else{
		    	$ret["error_code"] = "0";
		    	$ret["exists"] = "false";
		    }
		}

	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	}
	return $ret;
}
?>