<?

include "../api/v1/db.php";
include "../api/v1/projects.php";

$cmd = $_REQUEST["cmd"];

switch ($cmd) {
	case "blocks":
		   $res = GetIdContents();
    break;
}

 echo json_encode($res);
?>

