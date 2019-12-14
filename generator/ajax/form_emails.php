<?php
header('Access-Control-Allow-Origin: *');
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
include_once '../vendor/autoload.php';
include_once ("../config.php");


date_default_timezone_set('UTC');


function SendEmailForm($to, $subject, $message) 
{

	/*********************/
	/* Function made for */
	/* user submit forms */
	/*********************/

	global $aws_key, $aws_pass, $AWS_REGION;
	$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

	$sdk = new Aws\Sdk([
    'region'   => $AWS_REGION,
    'version'  => 'latest',
    'credentials' => $credentials
	]);

	$dynamodb = $sdk->createDynamoDb();
	$marshaler = new Marshaler();

	$code= $message;//data
	$subject = $subject; //subject
	$user_email = $to; //To

	// Replace sender@example.com with your "From" address.
	// This address must be verified with Amazon SES.
	$sender = EMAIL_FROM;										
	$senderName = EMAIL_FROM_NAME;

	// Replace recipient@example.com with a "To" address. If your account
	// is still in the sandbox, this address must be verified.
	$recipient = $user_email;

	// Replace smtp_username with your Amazon SES SMTP user name.
	$usernameSmtp = SMTP_USER;

	// Replace smtp_password with your Amazon SES SMTP password.
	$passwordSmtp = SMTP_PASS;

	// Specify a configuration set. If you do not want to use a configuration
	// set, comment or remove the next line.
	//$configurationSet = 'ConfigSet';

	// If you're using Amazon SES in a region other than US West (Oregon),
	// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
	// endpoint in the appropriate region.
	$host = 'email-smtp.'.AWS_REGION2.'.amazonaws.com';
	$port = 587;

	// The subject line of the email
	//$subject = 'Amazon SES test (SMTP interface accessed using PHP)';

	

	$mail = new PHPMailer(true);

	try {
	    // Specify the SMTP settings.
	    $mail->isSMTP();
	    $mail->setFrom($sender, $senderName); //from BD
	    $mail->Username   = $usernameSmtp;
	    $mail->Password   = $passwordSmtp;
	    $mail->Host       = $host;
	    $mail->Port       = $port;
	    $mail->SMTPAuth   = true;
	    $mail->SMTPSecure = 'tls';
	    $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

	    // Specify the message recipients.
	    $mail->addAddress($recipient);
	    // You can also add CC, BCC, and additional To recipients here.

	    // Specify the content of the message.
	    $mail->isHTML(true);
	    $mail->Subject    = $subject;   //change
	    $mail->Body       = $code;		//change
	    //$mail->AltBody    = $bodyText;

	  	//print_r($mail);
		$mail->Send();
		return "send";
		
	   //echo "Email sent!" , PHP_EOL;
	} catch (phpmailerException $e) {
        //echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
        return "error";
		
	} catch (Exception $e) {
        //echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
        return "error";
		
	}

}

?>