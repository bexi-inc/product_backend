<?

include "../api/v1/db.php";
include "../api/v1/projects.php";

$cmd = $_REQUEST["cmd"];

switch ($cmd) {
	case "blocks":
		   $res["data"] = GetIdContents();
		   $res["error"] = 0;
		   print_r($res["data"]);
    break;
}

 echo json_encode($res);
?>

