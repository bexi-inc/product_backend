<?

 header("Access-Control-Allow-Origin: *");
// this is just a test
//send back to the ajax request the request

	$data = json_encode($_POST);

	$to = $data["email_to"];
    $subject = "Modu - Form";
    $message = print_r($data,true);
    $from = "noreply@getmodu.com";
   	$headers = "From: getModu.com";

   	$res["error"] = "";
    if (mail($to, $subject, $message, $headers)) {
        $res["msj"] = "Mail Sent.";
    }
    else {
        $res["error"] = "failed";
    }

    echo json_encode($res);
?>