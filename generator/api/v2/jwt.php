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

	return JWT::encode($token, JWT_KEY);
}

function GetJWTTokenv2($data)
{
	$time = time();

	$token = array(
    	'iat' => $time, // Tiempo que inició el token
    	'exp' => $time + JWT_TIMEOUT, // Tiempo que expirará el token (+1 hora)
    	'data' => $data
	);

	$res["token"]= JWT::encode($token, JWT_KEY);
	$res["expiration"] = $token["exp"];
	$res["token_duration"] = JWT_TIMEOUT;
	return $res;
}

function DecodeJMT($token)
{
	try
	{
		return JWT::decode($token, JWT_KEY, array('HS256'));
	} catch (\Exception $e) {
		//print_r($e);
		$ret["error_msg"] = $e->getMessage();
	}
	
}

?>