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

function sortFunction( $a, $b ) {
    $a = strtotime($a["date_create"]);
    $b = strtotime($b["date_create"]);

    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
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
			$projects=array_reverse($projects);
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


function CreateNewProject($connDyn, $userid, $pname, $pgoal, $industry, $colors, $txtcolors,  $pkeywords , $pservices, $pemailcontact, $pfontprimary, $pfontsecondary, $offering, $goal)
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

	$Data .= ((isset($pemailcontact)) ? ', "email_contact" : "'.$pemailcontact.'"' : '');
	$Data .= ((isset($pfontprimary)) ? ', "font_primary" : "'.$pfontprimary.'"' : '');
	$Data .= ((isset($pfontsecondary)) ? ', "font_secondary" : "'.$pfontsecondary.'"' : '');

	$Data .= ((isset($offering)) ? ', "project_offering" : "'.$offering.'"' : '');
	$Data .= ((isset($goal)) ? ', "project_goal" : "'.$goal.'"' : '');

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
	global $Marshaler;
	include ("../../exports.php");

	$ret["error_code"] = "0";
	if ($type==1 && $subdomain!="")
	{

		ExportProject("dom",strval($idDev), $subdomain,"../../");


		if ($ProjId != "")
		{
			$key = '
		    {
		        "deliverable_id": "'.$idDev.'",
		        "project_id" : "'.$ProjId.'"
		    }
			';
		}else{
			$Data ='{
			":devId" : "'.$idDev.'"
			}';

			$table = ExecuteQuery("modu_deliverables",$Data,"deliverable_id = :devId", "" , "" , false);

			$key = '
		    {
		        "deliverable_id": "'.$idDev.'",
		        "project_id" : "'.$Marshaler->unmarshalValue($table["data"]['Items'][0]['project_id']).'"
		    }
			';

		}

		

		$updateData='{
			":subd" : "'.$subdomain.'",
			":domain_status" : "0"
		}';


		$paramsNoms["#value"] = "value";
		$resUpd = Update("modu_deliverables",$key,"set subdomain = :subd",$updateData, "", false);


		$Data = '{
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
		//$ret["link"]="http://".$subdomain.".getmodu.com.s3-website.us-east-2.amazonaws.com/";
		$ret["link"] = "http://".$subdomain.".getmodu.com/";
		$ret["aws_link"] = $subdomain.AWS_BUCKET_URL;

	}elseif ($type==2)
	{
		$ret["link"] = "http://generator.getmodu.com/exports.php?type=zip&devid=".$idDev;
	}
	
	return $ret;
}

function ExistDomain_publish($idDev)
{
	global $Marshaler;
	$ret["error_code"] = "0";

	$Data ='{
		":devId" : "'.$idDev.'"
	}';

	$table = ExecuteQuery("modu_deliverables",$Data,"deliverable_id = :devId","","",false);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
				$ret["error_code"] = "0";
				if(isset($dbdata[0]['subdomain']) && !is_null($dbdata[0]['subdomain']))
				{
					$ret["domain"] = $Marshaler->unmarshalValue($dbdata[0]['subdomain']);
					$ret["exists"] = "true";
					$ret["message"] = "subdomain already exists";
				}
				else
				{
					$ret["exists"] = "false";
					$ret["message"] = "subdomain do not exists";
				}
		    	return $ret;
		}else{
			$ret["error_code"] = "100";
		    $ret["message"] = "Deliverable not found";
		    return $ret;
		}
	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
}

function create_recipe($proj_id)
{
    $type=5;//get type of recipe
	global $Marshaler;
	$ret["error_code"] = "0";

	$userData ='{
		":id" : "'.$type.'"
	}';

	$table = ExecuteQuery("modu_recipes_lp",$userData,"id = :id", "" , "" , false);
	$parts = []; //array to save the parts
	$final=[];
	//print_r($table);

	if ($table["error"]=="")
	{
		$dbdata = $Marshaler->unmarshalValue($table["data"]['Items'][0]["part"]);
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
			//mix parts 1-2 and 3-6
			for ($i=0;$i < 7; $i++) {
				if($parts[$i]["number"]==$i)
				{
					$parttemp = [];
					$parttemp["number"] = -1;
					$parttemp["contents"];
					array_splice( $parts, $i, 0, $parttemp);
				}
			}
			$temparray=[];
			$temparray=array_slice($parts, 2,2);
			print_r($temparray);
			shuffle($temparray);
			array_splice( $parts, 1,2,$temparray);
			$temparray=array_slice($parts, 3,3);
			shuffle($temparray);
			array_splice( $parts, 3,3,$temparray);
			print_r($parts);

			//random pickup contents for each part
			foreach ($parts as $part) {
				$final [] =  $part["contents"][array_rand($part["contents"], 1)];//add the id-content random to the array
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