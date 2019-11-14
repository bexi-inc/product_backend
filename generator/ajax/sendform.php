<?

// this is just a test
//send back to the ajax request the request

	$data = json_encode($_POST);

	$to = "trislos@gmail.com";
    $subject = "Modu - Form";
    $message = print_r($data,false);
    $from = "noreply@getmodu.com";
   	$headers = "From: getModu.com";

    if (mail($to, $subject, $message, $headers)) {
        echo "Mail Sent.";
    }
    else {
        echo "failed";
    }
?>