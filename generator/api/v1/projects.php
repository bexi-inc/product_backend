<?

function GetStatusStr($status)
{
	swith($status)
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

function GetTypeStr($Type)
{
	swith($status)
	case 1:
		return "Landing Page";
		break;
	default:
		return "Undefine"
		break;
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
				$proj["type"] = GetTypeStr($Marshaler->unmarshalValue($project["type"]));
				$proj["date_create"] = GetTypeStr($Marshaler->unmarshalValue($project["date_create"]));
				echo date('Y-m-d H:i:s', $proj["date_create"]);
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

?>