<?

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
			
			foreach ($table["data"]['Items'] as $project) {
				$proj = []; 
				$proj["project _id"] = $Marshaler->unmarshalValue($project["project _id"]);
				$proj["project_name"] = $Marshaler->unmarshalValue($project["project_name"]);
				$proj["status"] = $Marshaler->unmarshalValue($project["status"]);
				$proj["industry"] = $Marshaler->unmarshalValue($project["industry"]);
				$projects [] = $proj;
			}
		}
	}
	$res["data"] = $projects;
	//print_r($res);
	return  $res;

}

?>