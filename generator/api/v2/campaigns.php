<?
/************************************************/
/******  ARCHIVO PARA EL MANEJO DE CAMPAÑAS *****/
/************************************************/

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function CreateCampaign($connDyn, $userid, $cname, $cgoal, $industry, $colors, $txtcolors,  $pkeywords , $cservices, $pemailcontact, $pfontprimary, $pfontsecondary, $offering, $goal, $ProjectsTypes)
{
	global $Marshaler;
	$pid = uniqid("",true);
	$Data ='{
		"id" : "'.$pid.'"
		,"user_id" : "'.$userid.'"
		,"date_create" : "'.$pid.'"
		,"campaign_name" : "'.$cname.'"
		,"campaign_industry" : "'.$industry.'" ';

	$Data .= ((trim($pgoal) != '' ) ? ',"campaign_goal" :  "'.$cgoal.'" ' : '' );

	$Data .= ((isset($_FILES["logo"]["name"])) ? ', "logofull" : "'.$_FILES["logo"]["name"].'"' : '');

	$Data .= ((isset($pemailcontact)) ? ', "email_contact" : "'.$pemailcontact.'"' : '');
	$Data .= ((isset($pfontprimary)) ? ', "font_primary" : "'.$pfontprimary.'"' : '');
	$Data .= ((isset($pfontsecondary)) ? ', "font_secondary" : "'.$pfontsecondary.'"' : '');

	$Data .= ((isset($offering)) ? ', "campaign_offering" : "'.$offering.'"' : '');
	$Data .= ((isset($goal)) ? ', "campaign_goal" : "'.$goal.'"' : '');
	$recipetype=Gettyperecipe($offering,$goal);
	$Data .=', "recipe_type" : "'.$recipetype.'"';
	$Data .= ((!empty($cservices)) ? ', "campaign_services" : "'.$cservices.'"' : '');

	$Data .= ' ,"status" : "0"
		,"colors" : [
				'.$colors.'
		]
		,"txtcolors" : [
				'.$txtcolors.'
		]
		,"keywords" : "'.$pkeywords.'"
		,"projects_types" : '.json_encode($ProjectsTypes).'
	';
	$Data = $Data . '}';


	//print_r($Data);

	$resIns=Insert("modu_campaigns",$Data, false);

	
	if (!$resIns["error"])
	{
		$res["error"]="0";
		$res["id"] =  $pid;


		if (!file_exists(PATHSERVER.$userid)) {
		    mkdir($path.$userid, 0777, true);
		}

		if (!file_exists(PATHSERVER.$userid."/".$pid)) {
		    mkdir($path.$userid."/".$pid, 0777, true);
		}

		$fullpath= PATHSERVER.$userid."/".$pid . "/";

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


function GetCampaigns($idUser)
{
	
	global $Marshaler;
	$ret["error_code"] = "0";

	$userData ='{
		":userid" : "'.$idUser.'"
	}';

	$table = ExecuteQuery("modu_campaigns",$userData,"user_id = :userid", "user_id-index" , "" , false);
	$projects = []; 


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

				//get deliverable from the project
				/*$userData2 ='{
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
				}*/


				$camp["name"] = $Marshaler->unmarshalValue($dbRes["campaign_name"]);
				$camp["keywords"] = $Marshaler->unmarshalValue($dbRes["keywords"]);
				$camp["campaign_goal"] = $Marshaler->unmarshalValue($dbRes["campaign_goal"]);
				$camp["campaign_offering"] = $Marshaler->unmarshalValue($dbRes["campaign_offering"]);
				$camp["status"] = $Marshaler->unmarshalValue($dbRes["status"]);
				$camp["deliverables_types"] = ((!empty($dbRes["projects_types"]) : ($Marshaler->unmarshalValue($dbRes["projects_types"])) ? "");
				//$proj["status"] = GetStatusStr($Marshaler->unmarshalValue($project["status"]));
				if (!empty($dbRes["campaign_industry"]))
				{
					$camp["industry"] = $Marshaler->unmarshalValue($dbRes["campaign_industry"]);
				}else{
					$camp["industry"] = "";
				}
				
				//$proj["type"] = "Landing Page";//GetTypeStr($Marshaler->unmarshalValue($project["type"]));


				if ($project["date_create"])
				{
					$micro_date = date($Marshaler->unmarshalValue($dbRes["date_create"]));
					$date_array = explode(".",$micro_date);
					$date = date("Y-m-d",$date_array[0]);
					$camp["create_date"] = $date;	
				}else{
					$camp["create_date"] = "Undefine";
				}
				

				//echo date('Y-m-d H:i:s', $proj["date_create"]);
				$projects [] = $camp;
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


?>