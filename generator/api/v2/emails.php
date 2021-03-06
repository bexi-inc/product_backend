<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
include_once ('../../vendor/autoload.php');
include_once ("../../config.php");


date_default_timezone_set('UTC');

/***************************************
Sent emails themes
type:
	1:	welcome
	2:	confirm_email
	3:	retrieve password
	4:	Confirm password changed
	5:	download html
	6:	published_project
***************************************/

function SendEmail($type,$user,$IdRef = 0, $data = [])
{
	global $aws_key, $aws_pass, $AWS_REGION;
	$credentials = new Aws\Credentials\Credentials($aws_key, $aws_pass);

	$sdk = new Aws\Sdk([
    'region'   => $AWS_REGION,
    'version'  => 'latest',
    'credentials' => $credentials
	]);

	$dynamodb = $sdk->createDynamoDb();
	$marshaler = new Marshaler();

	switch ($type) {
		case 1:
			$code=file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR ."email_themes/welcome.html");
			$subject = "Welcome to GetModu";
			break;
		case 2:
			$params = [
		        'TableName' => "bexi_prod_users",
		         "KeyConditionExpression"=> "id = :id",
		        "ExpressionAttributeValues"=> [
		            ":id" =>  ["S" => strval($user) ]
			    ]
			];

		    $result = $dynamodb->query($params);

		   
		    if (count($result['Items'])>0)
		    {
		    	$token = $marshaler->unmarshalValue($result['Items'][0]["email_token"]);
		    }

			$code=file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR ."email_themes/confirm_email.html");

			$code = str_replace("{link}","http://app.getmodu.com/#/?token_email=".$token,$code);
			
			$subject = "Confirm Email";
			break;
		case 3:
			$code=file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR ."email_themes/retrieve_password.html");
			$subject = "Retrieve Password";
			break;
		case 4:
			$code=file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR .dirname(__FILE__) . DIRECTORY_SEPARATOR ."email_themes/confirm_password_change.html");
			$subject = "Password Changed";
			break;
		case 5:
			$code=file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR ."email_themes/download_html.html");
			$subject = "Download";

			$params = [
		        'TableName' => "modu_deliverables",
		         "KeyConditionExpression"=> "deliverable_id = :id",
		        "ExpressionAttributeValues"=> [
		            ":id" =>  ["S" => strval($IdRef) ]
			    ]
			];

		    $result = $dynamodb->query($params);

		   
		    if (count($result['Items'])>0)
		    {
		    	$projectId = $marshaler->unmarshalValue($result['Items'][0]["project_id"]);

				$params = [
			        'TableName' => TBL_PROJECTS,
			         "KeyConditionExpression"=> "project_id = :id",
			        "ExpressionAttributeValues"=> [
			            ":id" =>  ["S" => strval($projectId)]
			        ]
			    ];

			    //print_r($params);

			    $result_proj = $dynamodb->query($params);

			   // print_r($result);
		    	//die();

			    $ProjectName="";

			    if (count($result_proj['Items'])>0)
	    		{
	    			$ProjectName = $marshaler->unmarshalValue($result_proj['Items'][0]["project_name"]);
	    			if ($user=-1)
	    			{
	    				$user = $marshaler->unmarshalValue($result_proj['Items'][0]["user_id"]);
	    			}
	    		}

	    		$code = str_replace("{project_name}",$ProjectName,$code);
				$code = str_replace("{link}",$data["link"],$code);
				
		    }else{
		    	return;
		    }
		    
			break;
		case 6:
			$code=file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR ."email_themes/published_project.html");
			$subject = "Project Published";

			$params = [
		        'TableName' => "modu_deliverables",
		         "KeyConditionExpression"=> "deliverable_id = :id",
		        "ExpressionAttributeValues"=> [
		            ":id" =>  ["S" => strval($IdRef) ]
			    ]
			];

		    $result = $dynamodb->query($params);

		   
		    if (count($result['Items'])>0)
		    {
		    	$projectId = $marshaler->unmarshalValue($result['Items'][0]["project_id"]);
		    	$subdomain = $marshaler->unmarshalValue($result["Items"][0]["subdomain"]);

				$params = [
			        'TableName' => TBL_PROJECTS,
			         "KeyConditionExpression"=> "project_id = :id",
			        "ExpressionAttributeValues"=> [
			            ":id" =>  ["S" => strval($projectId)]
			        ]
			    ];

			    //print_r($params);

			    $result_proj = $dynamodb->query($params);

			   // print_r($result);
		    	//die();

			    $ProjectName="";

			    if (count($result_proj['Items'])>0)
	    		{
	    			$ProjectName = $marshaler->unmarshalValue($result_proj['Items'][0]["project_name"]);
	    			if ($user=-1)
	    			{
	    				$user = $marshaler->unmarshalValue($result_proj['Items'][0]["user_id"]);
	    			}
	    		}

	    		$code = str_replace("{project_name}",$ProjectName,$code);
				$code = str_replace("{link}","http://".$subdomain.".".MAIN_DOMAIN,$code);
				
		    }else{
		    	return;
		    }

			break;
		default:
			# code...
			break;
	}

	// O
	$params = [
        'TableName' => "bexi_prod_users",
         "KeyConditionExpression"=> "id = :id",
        "ExpressionAttributeValues"=> [
            ":id" =>  ["S" => strval($user)],
        ]
    ];

    //print_r($params);
     $result = $dynamodb->query($params);

     //print_r($result['Items']);
     $user_email = $marshaler->unmarshalValue($result['Items'][0]["username"]);

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
	    $mail->setFrom($sender, $senderName);
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
	    $mail->Subject    = $subject;
	    $mail->Body       = $code;
	    //$mail->AltBody    = $bodyText;

	  	//print_r($mail);
	    $mail->Send();
	   //echo "Email sent!" , PHP_EOL;
	} catch (phpmailerException $e) {
	   // echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
	} catch (Exception $e) {
	   // echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
	}
}



	/*
	//include("../../config.php");
	// Replace sender@example.com with your "From" address.
	// This address must be verified with Amazon SES.
	$sender = 'noreply@getmodu.com';
	$senderName = 'GetModu';

	// Replace recipient@example.com with a "To" address. If your account
	// is still in the sandbox, this address must be verified.
	$recipient = 'trislos@gmail.com';

	// Replace smtp_username with your Amazon SES SMTP user name.
	$usernameSmtp = AWS_SMTP_USER;

	// Replace smtp_password with your Amazon SES SMTP password.
	$passwordSmtp = AWS_SMTP_PASSWORD;

	// Specify a configuration set. If you do not want to use a configuration
	// set, comment or remove the next line.
	//$configurationSet = 'ConfigSet';

	// If you're using Amazon SES in a region other than US West (Oregon),
	// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
	// endpoint in the appropriate region.
	$host = 'email-smtp.us-west-2.amazonaws.com';
	$port = 587;

	// The subject line of the email
	$subject = 'Amazon SES test (SMTP interface accessed using PHP)';

	// The plain-text body of the email
	$bodyText =  "Email Test\r\nThis email was sent through the
	    Amazon SES SMTP interface using the PHPMailer class.";

	// The HTML-formatted body of the email
	$bodyHtml = '<h1>Email Test</h1>
	    <p>This email was sent through the
	    <a href="https://aws.amazon.com/ses">Amazon SES</a> SMTP
	    interface using the <a href="https://github.com/PHPMailer/PHPMailer">
	    PHPMailer</a> class.</p>';

	$mail = new PHPMailer(true);

	try {
	    // Specify the SMTP settings.
	    $mail->isSMTP();
	    $mail->setFrom($sender, $senderName);
	    $mail->Username   = $usernameSmtp;
	    $mail->Password   = $passwordSmtp;
	    $mail->Host       = $host;
	    $mail->Port       = $port;
	    $mail->SMTPAuth   = true;
	    $mail->SMTPSecure = 'tls';
	   // $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

	    // Specify the message recipients.
	    $mail->addAddress($recipient);
	    // You can also add CC, BCC, and additional To recipients here.

	    // Specify the content of the message.
	    $mail->isHTML(true);
	    $mail->Subject    = $subject;
	    $mail->Body       = $bodyHtml;
	    $mail->AltBody    = $bodyText;
	    $mail->Send();
	    echo "Email sent!" , PHP_EOL;
	} catch (phpmailerException $e) {
	    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
	} catch (Exception $e) {
	    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
	}
}


if ($_REQUEST["test"])
{
	SendEmail($_REQUEST["type"],$_REQUEST["user"]);
}
*/
?>