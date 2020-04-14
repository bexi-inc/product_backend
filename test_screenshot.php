<?
include("includes/Chrome.php");

$Web = new Chrome();
$Web->Navigate("https://stackoverflow.com/");

$Web->SaveScreenShot("/var/www/uploads.getmodu.com/public_html/102/
");
?>