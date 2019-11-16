<?
session_start();
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");



require "../../config.php";
require '../../vendor/autoload.php';

require "db.php";
require "users.php";
require "emails.php";
require "projects.php";

$_REQ = json_decode(file_get_contents('php://input')); 

if (empty($_REQ))
{
	$_REQ = (object)$_REQUEST;
}

$res["error_code"]=0;

 switch ($_REQ->cmd) {
 	case 'SigIn':
 		// VALIDAMOS LOS DATOS MINIMOS
 		if (!isset($_REQ->email) || !isset($_REQ->password) || !isset($_REQ->name) || !isset($_REQ->last_name))  
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res=SigIn($Dynamodb,$_REQ->email, $_REQ->name, $_REQ->last_name, $_REQ->password);
 		
 		break;
 	case 'LogIn':
 		if (!isset($_REQ->email) || !isset($_REQ->password))  
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res=LogIn($Dynamodb,$_REQ->email, $_REQ->password);
 		
 		break;
 	case 'GetProfile':
 		if (!isset($_REQ->userid))  
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res=GetProfile($Dynamodb,$_REQ->userid);
 		break;
 	case 'ChangePassword':
 		if (!isset($_REQ->userid) || !isset($_REQ->password))  
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res=UpdatePassword($Dynamodb,$_REQ->userid, $_REQ->password);
 		
 		break;
 	case 'UpdateProfile':
 		if (!isset($_REQ->userid))  
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res=UpdateProfile($Dynamodb,$_REQ->userid, $_REQ->username, $_REQ->name, $_REQ->last_name, $_REQ->company, $_REQ->cellphone, $_REQ->ad_street, $_REQ->ad_city,$_REQ->ad_postalcode, $_REQ->ad_country, $_REQ->plan_id);
 		break;
 	case 'GetGmailLoginLink':
 		$res=GetGmailLoginLink();
 		break;
 	case 'GmailSigin':
 		if (!isset($_REQ->code))  
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res=GmailSigin($_REQ->code);
 		break;
 	case 'CreateSubdomain':
 		if (!isset($_REQ->subdomian))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res= CreateBucket($_REQ->subdomian);
 		break;
 	case 'SendEmail':
 		if (!isset($_REQ->type) || !isset($_REQ->user))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		if (isset($_REQ->project))
 		{
 			$project = $_REQ->project;
 		}else{
 			$project = 1;
 		}
 		$res= SendEmail($_REQ->type, $_REQ->user, $project);
 		break;
 	case 'NewRecoveryToken':
 		if (!isset($_REQ->user))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res= NewRecoveryToken($_REQ->user);
 		break;
 	case 'ValidateToken':
 		if (!isset($_REQ->token))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res= ValidateToken($_REQ->token);
 		break;
 	case 'GetProjects' :
 		if (!isset($_REQ->userid))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			
 		}
 		$res = GetProjects($_REQ->userid);
 		break;
 	case "CreateProject":
 		/*print_r($_REQ);
 		echo "files";
 		print_r($_FILES);*/
 		if (!isset($_REQ->userid) || !isset($_REQ->projectname) || !isset($_REQ->projectgoal) || !isset($_REQ->projectindustry) || !isset($_REQ->colors))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params CreateProject";
 			
 		}
 		//die("CreateProject");
 		$res = CreateProject($Dynamodb, $_REQ->userid, $_REQ->projectname, " ", $_REQ->projectindustry, $_REQ->brandcolors, $_REQ->textcolor, $_REQ->projectkeywords,  $_REQ->productorservice);
 		break;

 	case "CreateDeliverable":
 		/*print_r($_REQ);
 		echo "files";
 		print_r($_FILES);*/
 		if (!isset($_REQ->projectid) || !isset($_REQ->winnerid) || !isset($_REQ->loserid) || !isset($_REQ->type))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params CreateProject";
 			
 		}
 		//die("CreateProject");
 		$res = CreateDeliverable($_REQ->projectid, $_REQ->winnerid, $_REQ->loserid, $_REQ->type);
 		break;
 	case "ExistsDomain":
 		if (!isset($_REQ->domain))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			
 		}
 		$res = ExistDomain($_REQ->domain);
 		break;
 	case "DeployDeliverableToDomain":
 		if (!isset($_REQ->deliverable) || !isset($_REQ->domain))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			
 		}
 		$res = DeployDeliverable($_REQ->deliverable,1,$_REQ->domain);
 		break;
 	case "DeployDeliverableToDownload":
 		if (!isset($_REQ->delviverable))
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			
 		}
 		$res = DeployDeliverable($_REQ->deliverable,2);
 		break;
 	default:
 		echo "REQ";
 		print_r($_REQ);
 		echo "REQUEST";
 		print_r($_REQUEST);
 		echo "FILES";
 		print_r($_FILE);
 		$res["error_code"]="501";
 		$res["message"]="Invalid Command";
 		break;
 }

echo json_encode( $res );
die();

?>