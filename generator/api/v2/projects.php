<?

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function Gettyperecipe($offering, $goal)
{
	$type=1;

	if($offering=="App"||$offering=="SaaS")
	{
		if(strpos($goal,"Sales Leads")!==false||strpos($goal,"Sales inquiries")!==false||strpos($goal,"Meetings/Appointments")!==false){
			$type=1;
			return $type;
		}

		if(strpos($goal,"Installs")!==false||strpos($goal,"Users")!==false){
			$type=3;
			return $type;
		}

		if(strpos($goal,"Subscribers")!==false||strpos($goal,"Sign-up")!==false){
			$type=1;
			return $type;
		}

		if(strpos($goal,"Promote Content")!==false||strpos($goal,"Inbound Leads")!==false){
			$type=5;
			return $type;
		}

		if(strpos($goal,"Promote Video")!==false){
			$type=6;
			return $type;
		}
	}


	if($offering=="Service (Digital/Physical)")
	{
		if(strpos($goal,"Sales Leads")!==false||strpos($goal,"Sales inquiries")!==false||strpos($goal,"Meetings/Appointments")!==false){
			$type=2;
			return $type;
		}

		if(strpos($goal,"Subscribers")!==false||strpos($goal,"Sign-up")!==false){
			$type=2;
			return $type;
		}

		if(strpos($goal,"Promote Content")!==false){
			$type=5;
			return $type;
		}

		if(strpos($goal,"Promote Video")!==false){
			$type=6;
			return $type;
		}
	}

	if($offering=="Event")
	{
		if(strpos($goal,"Registrations")!==false||strpos($goal,"Attendees")!==false){
			$type=7;
			return $type;
		}

		if(strpos($goal,"Stay informed")!==false){
			$type=7;
			return $type;
		}

		if(strpos($goal,"Promote Event")!==false){
			$type=7;
			return $type;
		}

		if(strpos($goal,"Subscribers")!==false||strpos($goal,"Sign-up")!==false){
			$type=7;
			return $type;
		}
	}

	if($offering=="Product (Digital/Physical)")
	{
		if(strpos($goal,"Installs")!==false||strpos($goal,"Users")!==false){
			$type=8;
			return $type;
		}

		if(strpos($goal,"Promote my product")!==false||strpos($goal,"Views in my LP")!==false){
			$type=8;
			return $type;
		}
	}
	return $type;
}

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

function Delete_temporals($userid){
	global $Marshaler;
	//delete all temporally desings code
	$data='
	{
		":usr": "'.$userid.'"
	}
	';
	$paramsNoms["#user"] = "user";
	$table = ExecuteQuery("bexi_projects_tmp",$data,"#user = :usr","user-index",$paramsNoms,false);
	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
            foreach ($dbdata as $item) {
				$tempid = $Marshaler->unmarshalValue($item["id"]);
				$key='
					{
						"id": "'.$tempid.'",
						"user": "'.$userid.'"
					}
				';
				$resul=remove("bexi_projects_tmp",$key);
				if($resul["error"]!=="")
				{

					$ret["error_code"] = "600";
					$ret["message"] =  $resul["error"];
					return $ret;
				}
            }
		}
	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
}

function GetProjects($userId)
{

	Delete_temporals($userId);
	global $Marshaler;
	$ret["error_code"] = "0";

	$userData ='{
		":userid" : "'.$userId.'"
	}';

	$table = ExecuteQuery(TBL_PROJECTS,$userData,"user_id = :userid", "user_id-index" , "" , false);
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

				//get deliverable from the project
				$userData2 ='{
					":projectid" : "'.$proj["project_id"].'"
				}';
				$table2 = ExecuteQuery("modu_deliverables",$userData2,"project_id = :projectid", "lastproject_id-index" , "" , false);
				if ($table2["error"]=="")
				{
					$dbdata2 = $table2["data"]['Items'];
					if (count($dbdata2)>0)
					{
						$last=count($dbdata2)-1;
						$proj["deliverable_id"]=$Marshaler->unmarshalValue($dbdata2[$last]["deliverable_id"]);
						$proj["status"] =$Marshaler->unmarshalValue($dbdata2[$last]["dev_status"]);
						if(isset($dbdata2[$last]["subdomain"]))
						{
							$dom=$Marshaler->unmarshalValue($dbdata2[$last]["subdomain"]);
							if($dom!="")
							{
								if(isset($dbdata2[$last]["domain_status"])){
									$dom_status=$Marshaler->unmarshalValue($dbdata2[$last]["domain_status"]);
									if($dom_status==="1"){
										$proj["link"]="http://".$dom.".getmodu.com/";
									}
									else{
										$proj["link"]="http://".$dom.BEXI_BUCKET_URL;
									}
								}
							}
						}
					}
				}
				else
				{
					$ret["error_code"] = "500";
					$ret["message"] =  $table2["error"];
					return $ret;
				}


				$proj["project_name"] = $Marshaler->unmarshalValue($project["project_name"]);
				//$proj["status"] = GetStatusStr($Marshaler->unmarshalValue($project["status"]));
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


function CreateNewProject($connDyn, $pcampaign, $pname)
{
	global $Marshaler;
	$pid = uniqid("",true);
	$pDate = microtime(true);
	$Data ='{
		"project_id" : "'.$pid.'"
		,"campaign_id" : "'.$pcampaign.'"
		,"date_create" : "'.$pDate.'"
		,"project_name" : "'.$pname.'"';
	$Data = $Data . '}';


	//print_r($Data);

	$resIns=Insert(TBL_PROJECTS,$Data, false);

	
	if (!$resIns["error"])
	{
		$res["error"]="0";
		$res["id"] =  $pid;
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
	$userid=null;

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
			$userid=$Marshaler->unmarshalValue($dbdata[0]['user']);
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
		,"domain_status" : "0"
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
			":dstatus" : "0"
		}';


		$paramsNoms["#value"] = "value";
		$resUpd = Update("modu_deliverables",$key,"set subdomain = :subd, domain_status=:dstatus",$updateData, "", false);



		$Data = '{
				 "subdomain" : "'.$subdomain.'"
				,"deliverable_id" : "'.$idDev.'"
				,"domain_status" : "0"
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
		$ret["aws_link"] = $subdomain.BEXI_BUCKET_URL;

	}elseif ($type==2)
	{
		$ret["link"] = "http://generator.".MAIN_DOMAIN."/exports.php?type=zip&devid=".$idDev;
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
					$ret["status"] = 0;

					$Data ='{
						":subd" : "'.$ret["domain"].'"
					}';

					$tblDom = ExecuteQuery("modu_subdomains",$Data,"subdomain  = :subd","","",false);

					if ($tblDom["error"]=="")
					{
						$dbDoms = $tblDom["data"]['Items'];
					
						if (count($dbDoms)>0)
						{
							if(isset($dbDoms[0]['domain_status']))
							{
								$ret["status"] = $Marshaler->unmarshalValue($dbDoms[0]['domain_status']);
							}
						}
					}

				}
				else
				{
					$ret["exists"] = "false";
					$ret["status"] = "0";
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
	global $Marshaler;
	$ret["error_code"] = "0";
	$type=1;//get type of recipe
	$userData ='{
		":id" : "'.$proj_id.'"
	}';
	$table = ExecuteQuery(TBL_PROJECTS,$userData,"project_id = :id", "" , "" , false);
	if($table["error"]==""){
		$type = $Marshaler->unmarshalValue($table["data"]['Items'][0]["recipe_type"]);//get type of recipe
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}

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
			/********* Add espaces in missing parts **********/
			for ($i=0;$i <= 7; $i++) {
				if($parts[$i]["number"]!==$i)
				{
					$parttemp = [];
					$parttemp["number"] = -1;
					$parttemp["contents"];
					array_splice( $parts, $i, 0, $parttemp);
				}
			}
			/*********vary order between 2-3**********/
			$temparray=[];
			$temparray=array_slice($parts, 1,2);//copy part 2,3
			shuffle($temparray);
			array_splice( $parts,1,2,$temparray);
			/*********vary order between 4-7**********/
			$temparray=array_slice($parts, 3,4);//copy part 4,5,6,7
			shuffle($temparray);
			array_splice( $parts,3,4,$temparray);
			/********* remove espaces in missing parts **********/
			$newparts=[];
			for ($i=0;$i <= 7; $i++) {
				if(isset($parts[$i]["number"]))
				{
					$newparts[]=$parts[$i];
				}
			}
			//random pickup contents for each part
			foreach ($newparts as $part) {
				//check if its the first and if there is only one content on the list
				if(count($final)>0 && count($part["contents"])>1)
				{
					$last=$final[count($final)-1];//get last id
					$temp=$last;
					do {
						$temp=$part["contents"][array_rand($part["contents"], 1)];
					} while ($temp == $last);
					$final [] =$temp;
				}else{
					$final [] =  $part["contents"][array_rand($part["contents"], 1)];//add the id-content random to the array
				}
			}
		}
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
	$res["data"] = $final;
	//print_r($res);
	return  $res;

}

function ExistDeliverable($projectid,$type)
{
	global $Marshaler;
	$pid = microtime(true);
	

	$data='
	    {
			":projectid": "'.$projectid.'",
			":typ": "'.$type.'"
	    }
	';
	$paramsNoms["#type"] = "type";
	$table = ExecuteQuery("modu_deliverables",$data,"project_id = :projectid AND #type = :typ","type-project_id-index",$paramsNoms,false);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
			$res["error"]="0";
			$res["message"] = "Deliverable found";
			$res["deliverable_id"] =  $Marshaler->unmarshalValue($dbdata[0]['deliverable_id']);
			$res["dev_status"]=$Marshaler->unmarshalValue($dbdata[0]['dev_status']);
		}
		else
		{
			$res["error_code"] = "100";
		    $res["message"] = "Deliverable not found";
		    return $res;
		}
	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}
	return $res;
}

function EditDeliverable($deliverable_id,$project_id,$winner, $loser)
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
	

	$updateData ='{
		":datem" : "'.$pid.'"
		,":winnerid" : "'.$winner.'"
		,":htmlcode" : "'.$winner_code.'"
		,":loserid" : "'.$loser.'"
		,":losercode" : "'.$loser_code.'"
	';
	$updateData = $updateData . '}';

	$updateQ ='set date_modified =:datem,winner_id =:winnerid,html_code =:htmlcode,loser_id =:loserid,loser_code =:losercode';

	
	$key = '
	    {
			"deliverable_id": "'.$deliverable_id.'",
			"project_id" : "'.$project_id.'"
	    }
	';

	$resUpd = Update("modu_deliverables",$key,$updateQ,$updateData,"",false);

	if (!$resUpd["error"])
	{
		$ret["error_code"]="0";
		$ret["message"] = "Deliverable updated";
	}else{
		$ret["error_code"]="500";
		$ret["message"] =  $resUpd["error"];
	}
	return $ret;
}

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

?>