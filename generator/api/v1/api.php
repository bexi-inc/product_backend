<?
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

require "../../config.php";
require '../../vendor/autoload.php';

require "db.php";
require "users.php";
require "emails.php";

$_REQ = json_decode(file_get_contents('php://input')); 

$res["error_code"]=0;

 switch ($_REQ->cmd) {
 	case 'SigIn':
 		// VALIDAMOS LOS DATOS MINIMOS
 		if (!isset($_REQ->email) || !isset($_REQ->password) || !isset($_REQ->name) || !isset($_REQ->lastname))  
 		{
 			$res["error_code"]="502";
 			$res["message"]="Invalid params";
 			break;
 		}
 		$res=SigIn($Dynamodb,$_REQ->email, $_REQ->name, $_REQ->lastname, $_REQ->password);
 		
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
 		$res= SendEmail($_REQ->type, $_REQ->user, $_REQ->project);
 		break;
 	default:
 		$res["error_code"]="501";
 		$res["message"]="Invalid Command";
 		break;
 }

echo json_encode( $res );
die();

?>