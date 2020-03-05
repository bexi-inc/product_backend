<?
/************************************************/
/******  ARCHIVO PARA EL MANEJO DE CAMPAÑAS *****/
/************************************************/

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

function CreateCampaign($connDyn, $userid, $pname, $pgoal, $industry, $colors, $txtcolors,  $pkeywords , $pservices, $pemailcontact, $pfontprimary, $pfontsecondary, $offering, $goal)
{
	global $Marshaler;
	$pid = uniqid("",true);
	$Data ='{
		"id" : "'.$pid.'"
		,"user_id" : "'.$userid.'"
		,"date_create" : "'.$pid.'"
		,"campaign_name" : "'.$pname.'"
		,"industry" : "'.$industry.'" ';

	$Data .= ((trim($pgoal) != '' ) ? ',"project_goal" :  "'.$pgoal.'" ' : '' );

	$Data .= ((isset($_FILES["logofullcolor"]["name"])) ? ', "logofull" : "'.$_FILES["logofullcolor"]["name"].'"' : '');

	$Data .= ((isset($pemailcontact)) ? ', "email_contact" : "'.$pemailcontact.'"' : '');
	$Data .= ((isset($pfontprimary)) ? ', "font_primary" : "'.$pfontprimary.'"' : '');
	$Data .= ((isset($pfontsecondary)) ? ', "font_secondary" : "'.$pfontsecondary.'"' : '');

	$Data .= ((isset($offering)) ? ', "project_offering" : "'.$offering.'"' : '');
	$Data .= ((isset($goal)) ? ', "project_goal" : "'.$goal.'"' : '');
	$recipetype=Gettyperecipe($offering,$goal);
	$Data .=', "recipe_type" : "'.$recipetype.'"';

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


?>