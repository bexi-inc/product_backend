<?


function SigIn($connDyn, $email, $name, $lastname, $password)
{
	global $Marshaler;
	$ret["error_code"] = "0";

	$data='
	    {
	        ":username": "'.$email.'"
	    }
	';

	$table = ExecuteQuery("users",$data,"username = :username","username-index");

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
		    if ($Marshaler->unmarshalValue($dbdata[0]['username'])==$email)
		    {
		    	$ret["error_code"] = "101";
		    	$ret["message"] = "user email already exists";
		    	return $ret;
		    }else{
		    	$ret["error_code"] = "1";
		    }
		}

	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}

	$params='
	    {
	        ":nam": "user_id"
	    }
	';

	$table = ExecuteQuery("vars",$params, 'var_name = :nam');
	$dbdata = $table["data"]['Items'];
	if (count($dbdata)>0)
	{
		$userid =  $Marshaler->unmarshalValue($dbdata[0]['value']);
	}
	
	$userData ='{
		"id" : "'.$userid.'",
		"username" : "'.$email.'",
		"name" :  "'.$name.'",
		"last_name" : "' . $lastname . '",
		"password" : "'.password_hash($password,PASSWORD_DEFAULT).'"
	}';

	$resIns=Insert("users",$userData);

	if (!$resIns["error"])
	{
		$ret["user_id"] = $userid;
		$userid = $userid+1;

		$key = '
		    {
		        "var_name": "user_id"
		    }
		';

		$updateData='{
			":val" : "'.$userid.'"
		}';

		$paramsNoms["#value"] = "value";
		$resUpd = Update("vars",$key,"set #value = :val",$updateData,$paramsNoms);

		if ($resUpd["error"])
		{
			//print_r($resUpd);
		}
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $resIns["error"];
	    return $ret;
	}

	
	return $ret;
}


function Login($connDyn, $email, $password)
{
	global $Marshaler;
	$ret["error_code"] = "0";

	$userData ='{
		":usr" : "'.$email.'"
	}';

	$table = ExecuteQuery("users",$userData,"username = :usr","username-index");

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
		    if (password_verify($password,$Marshaler->unmarshalValue($dbdata[0]['password'])))
		    {
		    	$ret["error_code"] = "0";
		    	$user["id"] = $Marshaler->unmarshalValue($dbdata[0]['id']);
		    	$user["username"] = $Marshaler->unmarshalValue($dbdata[0]['username']);

		    	$ret["user"] = $user;
		    	return $ret;
		    }else{
		    	$ret["error_code"] = "100";
		    	$ret["message"] = "incorrect user or password";
		    	return $ret;
		    }
		}else{
			$ret["error_code"] = "100";
		    $ret["message"] = "incorrect user or password";
		    return $ret;
		}

	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}

}


function GetProfile($connDyn, $userId)
{
	global $Marshaler;
	$ret["error_code"] = "0";

	$userData ='{
		":usrid" : "'.$userId.'"
	}';

	$table = ExecuteQuery("users",$userData,"id = :usrid");

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
		    	$ret["error_code"] = "0";

		    	$user["id"] = $Marshaler->unmarshalValue($dbdata[0]['id']);
		    	$user["username"] = $Marshaler->unmarshalValue($dbdata[0]['username']);
		    	$user["name"] = isset($dbdata[0]['name']) ? $Marshaler->unmarshalValue($dbdata[0]['name']) : '';
		    	$user["last_name"] = isset($dbdata[0]['last_name']) ? $Marshaler->unmarshalValue($dbdata[0]['last_name']) : '';
		    	$user["company"] = isset($dbdata[0]['company']) ? $Marshaler->unmarshalValue($dbdata[0]['company']) : '';
		    	$user["cellphone"] = isset($dbdata[0]['cellphone']) ? $Marshaler->unmarshalValue($dbdata[0]['cellphone']) : '';
		    	$user["ad_street"] = isset($dbdata[0]['ad_street']) ? $Marshaler->unmarshalValue($dbdata[0]['ad_street']) : '';
		    	$user["ad_city"] = isset($dbdata[0]['ad_city']) ? $Marshaler->unmarshalValue($dbdata[0]['ad_city']) : '';
		    	$user["ad_postalcode"] = isset($dbdata[0]['ad_postalcode']) ? $Marshaler->unmarshalValue($dbdata[0]['ad_postalcode']) : '';
		    	$user["ad_country"] = isset($dbdata[0]['ad_country']) ? $Marshaler->unmarshalValue($dbdata[0]['ad_country']) : '';
		    	$user["plan_id"] = isset($dbdata[0]['plan_id']) ? $Marshaler->unmarshalValue($dbdata[0]['plan_id']) : '';

		    	$ret["user"] = $user;
		    	return $ret;
		}else{
			$ret["error_code"] = "100";
		    $ret["message"] = "wrong user id";
		    return $ret;
		}

	}
	else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	    return $ret;
	}

}


function UpdatePassword($connDyn, $userid, $password)
{
	$ret["error_code"] = "0";
	$ret["user_id"] = $userid;
	$userid = $userid+1;
	
	$key = '
	    {
	        "id": "'.$userid.'"
	    }
	';

	$updateData='{
		":pwd" : "'.password_hash($password,PASSWORD_DEFAULT).'"
	}';

	$paramsNoms["#value"] = "value";
	$resUpd = Update("users",$key,"set password = :pwd",$updateData);

	if (!$resUpd["error"])
	{
		$ret["message"] = "password updated";
	}else{
		$ret["message"] =  $resUpd["error"];
	}
	return $ret;
}



function UpdateProfile($connDyn, $userid, $username, $name, $last_name, $company, $cellphone, $ad_street, $ad_city, $ad_postalcode, $ad_country, $planid)
{
	$ret["error_code"] = "0";
	$ret["user_id"] = $userid;
	
	$key = '
	    {
	        "id": "'.$userid.'"
	    }
	';
	$updateQ = "set ";

	$sep = '';
	$updateData  = '{';
	if (isset($username) && !empty($username))
	{
		$updateData = $updateData.$sep.' ":user" : "'.$username .'"';
		$updateQ = $updateQ . $sep . " username = :user";
		$sep= ",";
	}
	if (isset($name) && !empty($name))
	{
		$updateData = $updateData.$sep.' ":fname" : "'.$name .'"';
		$updateQ = $updateQ . $sep . " #name= :fname";
		$sep= ",";
	}
	if (isset($last_name) && !empty($last_name))
	{
		$updateData = $updateData.$sep.' ":lname" : "'.$last_name .'"';
		$updateQ = $updateQ . $sep . " last_name = :lname";
		$sep= ",";
	}
	if (isset($company) && !empty($company))
	{
		$updateData = $updateData.$sep.' ":comp" : "'.$company .'"';
		$updateQ = $updateQ . $sep . " company = :comp";
		$sep= ",";
	}	
	if (isset($cellphone) && !empty($cellphone))
	{
		$updateData = $updateData.$sep.' ":cell" : "'.$cellphone .'"';
		$updateQ = $updateQ . $sep . " cellphone = :cell";
		$sep= ",";
	}
	if (isset($ad_street) && !empty($ad_street))
	{
		$updateData = $updateData.$sep.' ":street" : "'.$ad_street .'"';
		$updateQ = $updateQ . $sep . "  ad_street = :street";
		$sep= ",";
	}
	if (isset($ad_city) && !empty($ad_city))
	{
		$updateData = $updateData.$sep.' ":city" : "'.$ad_city .'"';
		$updateQ = $updateQ . $sep . "ad_city = :city";
		$sep= ",";
	}
	if (isset($ad_postalcode) && !empty($ad_postalcode))
	{
		$updateData = $updateData.$sep.' ":postalcode" : "'.$ad_postalcode .'"';
		$updateQ = $updateQ . $sep . " ad_postalcode = :postalcode";
		$sep= ",";
	}
	if (isset($ad_country) && !empty($ad_country))
	{
		$updateData = $updateData.$sep.' ":country" : "'.$ad_country .'"';
		$updateQ = $updateQ . $sep . "ad_country = :country";
		$sep= ",";
	}
	if (isset($planid) && !empty($planid))
	{
		$updateData = $updateData.$sep.' ":pid" : "'.$planid .'"';
		$updateQ = $updateQ . $sep . " plan_id = :pid";
		$sep= ",";
	}

	$updateData  = $updateData.'}';


	//print_r($updateData);
	//echo $updateQ;

	$paramsNoms["#name"] = "name";


	$resUpd = Update("users",$key, $updateQ, $updateData, $paramsNoms);

	//print_r($resUpd);

	if (!$resUpd["error"])
	{
		$ret["message"] = "profiel updated";
	}else{
		$ret["message"] =  $resUpd["error"];
	}
	return $ret;
}


function GetGmailLoginLink()
{ 
	$client = new Google_Client();
	$client->setClientId(GMAIL_CLIENT_ID);
	$client->setClientSecret(GMAIL_CLIENT_SECRET);
	$client->setRedirectUri(GMAIL_CLIENT_REDIRECT_URL);
	$client->addScope("email");
	$client->addScope("profile");
	$res["error"]=0;
	$res["link"]= $client->createAuthUrl();
	return $res;
}


function GmailSigin($code, $redirect="")
{
	global $Marshaler;


	$client = new Google_Client();
	$client->setClientId(GMAIL_CLIENT_ID);
	$client->setClientSecret(GMAIL_CLIENT_SECRET);
	if ($redirect!="")
	{
		$client->setRedirectUri($redirect);
	}else{
		$client->setRedirectUri(GMAIL_CLIENT_REDIRECT_URL);
	}
	
	$client->addScope("email");
	$client->addScope("profile");
	//$ret["error"]=0;
	//$res["link"]= $client->createAuthUrl();
	$token = $client->fetchAccessTokenWithAuthCode($code);
	//print_r($token);
	if ($token["error"])
	{
		$ret["error"]= $token["error"]." - ".$token["error_description"];
	}else
	{
		$client->setAccessToken($token['access_token']);

		// get profile info
		$google_oauth = new Google_Service_Oauth2($client);
		$google_account_info = $google_oauth->userinfo->get();
		//print_r($google_account_info);
		$email =  $google_account_info->email;
		$name =  $google_account_info->name;
		$idgoogle =  $google_account_info->id;

		$ret["error_code"] = "0";
		$ret["token"] = $token['access_token'];	

		$data='
	    {
	        ":username": "'.$email.'"
	    }
		';
		$table = ExecuteQuery("users",$data,"username = :username","username-index");

		if ($table["error"]=="")
		{
			$dbdata = $table["data"]['Items'];
			if (count($dbdata)>0)
			{
			    if ($Marshaler->unmarshalValue($dbdata[0]['username'])==$email)
			    {
			    	$ret["error_code"] = "0";
			    	$ret["message"] = "";
			    	$ret["user_id"] = $Marshaler->unmarshalValue($dbdata[0]['id']);
			    	return $ret;
			    }else{
			    	$ret["error_code"] = "1";
			    }
			}

		}
		else{
			$ret["error_code"] = "500";
		    $ret["message"] =  $table["error"];
		    return $ret;
		}

		$params='
	    {
	        ":nam": "user_id"
	    }
		';

		$table = ExecuteQuery("vars",$params, 'var_name = :nam');
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
			$userid =  $Marshaler->unmarshalValue($dbdata[0]['value']);
		}
		
		$userData ='{
			"id" : "'.$userid.'",
			"username" : "'.$email.'",
			"google_token" : "'.$idgoogle.'"
		}';

		$resIns=Insert("users",$userData);

		if (!$resIns["error"])
		{
			$ret["user_id"] = $userid;
			$userid = $userid+1;

			$key = '
			    {
			        "var_name": "user_id"
			    }
			';

			$updateData='{
				":val" : "'.$userid.'"
			}';

			$paramsNoms["#value"] = "value";
			$resUpd = Update("vars",$key,"set #value = :val",$updateData,$paramsNoms);

			if ($resUpd["error"])
			{
				//print_r($resUpd);
			}
		}else{
			$ret["error_code"] = "500";
		    $ret["message"] =  $resIns["error"];
		    return $ret;
		}
		
	}
	return $ret;
	
}

function NewRecoveryToken($user)
{
	$token = bin2hex(openssl_random_pseudo_bytes(64));
	$Data ='{
		"token" : "'.$token.'",
		"create_date" : "'.date("U").'",
		"user" :  "'.$user.'"
	}';

	$resIns=Insert("modu_tokens",$Data, false);

	$ret["error_code"] = "0";
	$ret["token"] =  $token;
	return $ret;
}

function ValidateToken($token)
{
	global $Marshaler;
	$ret["error_code"] = "0";

	$Data ='{
		":tk" : "'.$token.'"
	}';


	$paramsNoms["#token"] = "token";
	$table = ExecuteQuery("modu_tokens",$Data,"#token = :tk","",$paramsNoms,false);

	if ($table["error"]=="")
	{
		$dbdata = $table["data"]['Items'];
		if (count($dbdata)>0)
		{
			if ( (date("U") - $Marshaler->unmarshalValue($dbdata[0]['create_date'])) <= TOKEN_LIFE)
		    {
		    	$ret["error_code"] = "0";
		    	$ret["token"] = $Marshaler->unmarshalValue($dbdata[0]['token']);
		    	$ret["user"] =$Marshaler->unmarshalValue($dbdata[0]['user']);
		    }else{
		    	 $ret["error_code"] = "501";
	   			 $ret["message"] =  "Invalid Token";
		    }
		}
	}else{
		$ret["error_code"] = "500";
	    $ret["message"] =  $table["error"];
	   // return $ret;
	}

	return $ret;
}
?>