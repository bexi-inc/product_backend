<?php

 header("Access-Control-Allow-Origin: *");
// this is just a test
//send back to the ajax request the request

    $data = json_encode($_POST);
    
    $data = json_decode($data,true);

	$to = $data["email_to"];
    $subject = "Modu - Form";
    $data = json_encode($data);
    $message = print_r($data,true);
    $from = "noreply@getmodu.com";
   	$headers = "From: getModu.com";

    if (mail($to, $subject, $message, $headers)) {
        $res["error"] = 0;
	    $res["error_msj"] = "Mail Sent.";
    }
    else {
        $res["error"] = 500;
	    $res["error_msj"] = "Unable to send";
    }

    echo json_encode($res);
?>