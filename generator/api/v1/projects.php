<?

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

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

function GetProjects($userId)
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
		//print_r($dbdata);
		if (count($dbdata)>0)
		{
			$res["error"]=0;
			foreach ($table["data"]['Items'] as $project) {
				$proj = []; 
				$proj["project_id"] = $Marshaler->unmarshalValue($project["project_id"]);
				$proj["project_name"] = $Marshaler->unmarshalValue($project["project_name"]);
				$proj["status"] = GetStatusStr($Marshaler->unmarshalValue($project["status"]));
				$proj["industry"] = $Marshaler->unmarshalValue($project["industry"]);
				$proj["type"] = "Landing Page";//GetTypeStr($Marshaler->unmarshalValue($project["type"]));

				if ($project["date_create"])
				{
					$micro_date = date($Marshaler->unmarshalValue($project["date_create"]));
					$date_array = explode(".",$micro_date);
					$date = date("Y-m-d",$date_array[0]);
					$proj["create_date"] = $date;	
				}else{
					$proj["create_date"] = "Undefine";
				}
				

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


function CreateNewProject($connDyn, $userid, $pname, $pgoal, $industry, $colors, $txtcolors,  $pkeywords , $pservices)
{
	global $Marshaler;
	$pid = microtime(true);
	$Data ='{
		"project_id" : "'.$pid.'"
		,"user_id" : "'.$userid.'"
		,"date_create" : "'.$pid.'"
		,"project_name" : "'.$pname.'"
		,"industry" : "'.$industry.'" ';

	$Data .= ((trim($pgoal) != '' ) ? ',"project_goal" :  "'.$pgoal.'" ' : '' );

	$Data .= ((isset($_FILES["logofullcolor"]["name"])) ? ', "logofull" : "'.$_FILES["logofullcolor"]["name"].'"' : '');
	

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
		$res["error"]="0";
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
	global $Marshaler;
	$data='
	    {
	        ":sdom": "'.$domain.'"
	    }
	';

	$table = ExecuteQuery("modu_subdomains",$data,"subdomain = :sdom","","",false);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];

		if (count($dbdata)>0)
		{
		    if ($Marshaler->unmarshalValue($dbdata[0]['subdomain'])==$domain)
		    {
		    	$ret["error_code"] = "0";
		    	$ret["exists"] = "true";
		    	 $ret["message"] = "subdomain already exists";
		    	return $ret;
		    }else{
		    	$ret["error_code"] = "0";
		    	$ret["exists"] = "false";
		    }
		}else{
			$ret["error_code"] = "0";
			$ret["exists"] = "false";
		}

	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	}
	return $ret;
}


function CreateDeliverable($projectid, $winner, $loser, $type)
{
	global $Marshaler;
	$pid = microtime(true);
	

	$data='
	    {
	        ":id": "'.$winner.'"
	    }
	';

	$table = ExecuteQuery("bexi_projects_tmp",$data,"id = :id","","",false);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
		    $winner_code = $Marshaler->unmarshalValue($dbdata[0]['code']);
		}
	}

	$data='
	    {
	        ":id": "'.$loser.'"
	    }
	';

	$table = ExecuteQuery("bexi_projects_tmp",$data,"id = :id","","",false);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
		    $loser_code = $Marshaler->unmarshalValue($dbdata[0]['code']);
		}
	}
	
	
	//print_r($Data);


	$Data ='{
		"deliverable_id" : "'.$pid.'"
		,"project_id" : "'.$projectid.'"
		,"date_create" : "'.$pid.'"
		,"dev_status" : "0"
		,"winner_id" : "'.$winner.'" 
		,"html_code" : "'.$winner_code.'" 
		,"loser_id" : "'.$loser.'" 
		,"loser_code" : "'.$loser_code.'" 
		,"type" : "'.$type.'" 
	';
	$Data = $Data . '}';

	//print_r($Data);

	$resIns=Insert("modu_deliverables",$Data, false);

	$res["error"]="0";
	$res["id"] =  $pid;

	return $res;
}

function DeployDeliverable($idDev, $ProjId, $type, $subdomain="")
{
	global $AWS_REGION;
	global $aws_pass;
	global $aws_key;
	include ("../../exports.php");
	$ret["error_code"] = "0";
	if ($type==1 && $subdomain!="")
	{

		ExportProject("dom",strval($idDev), $subdomain,"../../");


		if ($ProjId !== "")
		{
			$key = '
		    {
		        "deliverable_id": "'.$idDev.'",
		        "project_id" : "'.$ProjId.'"
		    }
			';

			echo "Si existe ".$ProjId;
		}else{
			$Data ='{
			":devId" : "'.$idDev.'"
			}';

			$table = ExecuteQuery("modu_deliverables",$Data,"deliverable_id = :devId", "" , "" , false);

			print_r($table);
			$key = '
		    {
		        "deliverable_id": "'.$idDev.'",
		        "project_id" : "'.$Marshaler->unmarshalValue($table["data"]['Items'][0]['project_id']).'"
		    }
			';
		}


		print_r($key);
		

		$updateData='{
			":subd" : "'.$subdomain.'"
		}';
		$paramsNoms["#value"] = "value";
		$resUpd = Update("modu_deliverables",$key,"set subdomain = :subd",$updateData, "", false);

		print_r($resUpd);

		$Data .= '{
				 "subdomain" : "'.$subdomain.'"
				,"deviverable_id" : "'.$idDev.'"
		}';

		Insert("modu_subdomains",$Data,false);
		
		if (!$resUpd["error"])
		{
			$ret["message"] = "Ok";
			$ret["link"] = "http://".$subdomain.".getmodu.com/";
		}else{
			$ret["error"] = "500";
			$ret["message"] =  $resUpd["error"];
		}

	}elseif ($type==2)
	{
		$ret["link"] = "http://generator.getmodu.com/exports.php?Type=zip&devid=".$idDev;
	}
	
	return $ret;
}

?>