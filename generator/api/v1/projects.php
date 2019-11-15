<?

function GetProjects($connDyn, $userId)
{
	global $Marshaler;
	$ret["error_code"] = "0";

	$userData ='{
		":usrid" : "'.$userId.'"
	}';

	print_r($userData);
	$table = ExecuteQuery("modu_projects",$userData,"user_id = :usrid", , , false);
	$projects = []; 


	print_r($table);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
			
			foreach ($table["data"]['Items'] as $project) {
				$proj = []; 
				$proj["project _id"] = $project_name["project _id"];
				$proj["project_name"] = $project_name["project_name"];
				$proj["status"] = $project_name["status"];
				$proj["industry"] = $project_name["industry"];
				$projects [] = $proj;
			}
		}
	}
	$res["data"] = $projects;
	return  $res;

}

?>