<?

$path = "/var/www/uploads.getmodu.com/public_html/";
$webpath = "http://uploads.getmodu.com/";
//print_r($_REQUEST);


//print_r($_FILES);


if (!isset($_REQUEST["userid"]) || !isset($_REQUEST["projectid"]) || !isset($_FILES))
{
	die("Incorrect params");
}

$idfile =  (!isset($_REQUEST["tagid"])) ? uniqid() : $_REQUEST["tagid"];


if (!$idfile || is_null($idfile) || $idfile== "null")
{
	$idfile = uniqid();
}

if (!file_exists($path.$_REQUEST["userid"])) {
    mkdir($path.$_REQUEST["userid"], 0777, true);
}

if (!file_exists($path.$_REQUEST["userid"]."/".$_REQUEST["projectid"])) {
    mkdir($path.$_REQUEST["userid"]."/".$_REQUEST["projectid"], 0777, true);
}

$fullpath= $path.$_REQUEST["userid"]."/".$_REQUEST["projectid"] . "/";


$target_file = $fullpath . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$target_file = $fullpath."/".$idfile.".".$imageFileType;
$webpath = $webpath.$_REQUEST["userid"]."/".$_REQUEST["projectid"] . "/".$idfile.".".$imageFileType;
//print_r($_FILES);

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

}

$res["src"] = $webpath;
$res["id"] = $idfile;
$res["link"]="";

echo stripslashes(json_encode($res));

?>