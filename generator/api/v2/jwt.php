<?
use Firebase\JWT\JWT;

function GetJWTToken($data)
{
	$time = time();

	$token = array(
    	'iat' => $time, // Tiempo que inició el token
    	'exp' => $time + JWT_TIMEOUT, // Tiempo que expirará el token (+1 hora)
    	'data' => $data
	);

	return JWT::encode($token, $JWT_KEY);
}

?>