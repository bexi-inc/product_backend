<?
die();
include "includes/global.php";


$dir_codes="o_code/";
$modules[]="blg";
$modules[]="con";
$modules[]="cta";
$modules[]="ctt";
$modules[]="eco";
$modules[]="fea";
$modules[]="foo";
$modules[]="gal";
$modules[]="hea";
$modules[]="int";
$modules[]="pri";
$modules[]="sta";
$modules[]="tes";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
foreach ($modules as $mod)
{
	echo "buscando=".$dir_codes.$mod."*.html<br>";
	$files = glob($dir_codes.$mod."*.html");		
	echo $mod."<br>";	
	foreach ($files as $file){
		$code= str_replace(".html","",str_replace($dir_codes.$mod, "", $file));		
		$file_con = file_get_contents($file);
		$hojacss="";
		$contenido="";
		$file_con = preg_replace('~<script[^>]*>(.*?)</script>~si', "",$file_con);

		if (preg_match('~<body[^>]*>(.*?)</body>~si', $file_con, $body))
		{
		   // echo $body[1];
			$contenido = "<div class='".$mod."'>".$body[1]."</div>";
		}
		if (preg_match('~<link href="([^"]*?)" rel="stylesheet" type="text/css"/>~si', $file_con, $css))
		{
			$hojacss=$css[1];		    
		}

		if (!$mysqli->query("INSERT INTO  components(codigo,tipo,html,css,archivo) VALUES ('". $mysqli->real_escape_string($code)."','".$mysqli->real_escape_string($mod)."','". $mysqli->real_escape_string($contenido)."','". $mysqli->real_escape_string($hojacss)."','". $mysqli->real_escape_string($file)."')"))

		{
			 printf("Error: %s\n", $mysqli->error);
		}
	}
}


//$content = file_get_contents ($files[0]);

//print_r($content);
//print_r($files);
?>