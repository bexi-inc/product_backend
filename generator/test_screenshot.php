<?
include("includes/Chrome.php");

$Web = new Chrome();
if ($_REQUEST["web"])
{
	$Web->Navigate($_REQUEST["web"]);
}else{
	$Web->Navigate("https://stackoverflow.com/");
}


$Web->SaveScreenShot("/var/www/uploads.getmodu.com/public_html/102/
thumbnail.png");

echo "<IMG src='/var/www/uploads.getmodu.com/public_html/102/
thumbnail.png' />"
?>