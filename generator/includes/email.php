<?

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
require '../vendor/autoload.php';
require_once ("../config.php");

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
function SentEmail($type,$userId, $delev)
{
	switch ($type) {
		case 1:
			$code=file_get_contents("email_themes/welcome.html");
			$subject = "Welcome to GetModu";
			break;
		case 2:
			$code=file_get_contents("email_themes/confirm_email.html");
			$subject = "Confirm Email";
			break;
		case 3:
			$code=file_get_contents("email_themes/retrieve_password.html");
			$subject = "Retrieve Password";
			break;
		case 4:
			$code=file_get_contents("email_themes/confirm_password_change.html");
			$subject = "Password Changed";
			break;
		case 5:
			$code=file_get_contents("email_themes/download_html.html");
			$subject = "Download";
			break;
		case 6:
			$code=file_get_contents("email_themes/published_project.html");
			$subject = "Project Published";
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
            ":id" =>  ["S" => $userId],
        ]
    ];

     $result = $dynamodb->query($params);

     $user_email = $marshaler->unmarshalValue($result['Items'][0]["username"]);

	// Replace sender@example.com with your "From" address.
	// This address must be verified with Amazon SES.
	$sender = EMAIL_FROM;
	$senderName = EMAIL_FROM_NAME;

	// Replace recipient@example.com with a "To" address. If your account
	// is still in the sandbox, this address must be verified.
	$recipient = 'recipient@example.com';

	// Replace smtp_username with your Amazon SES SMTP user name.
	$usernameSmtp = 'smtp_username';

	// Replace smtp_password with your Amazon SES SMTP password.
	$passwordSmtp = 'smtp_password';

	// Specify a configuration set. If you do not want to use a configuration
	// set, comment or remove the next line.
	//$configurationSet = 'ConfigSet';

	// If you're using Amazon SES in a region other than US West (Oregon),
	// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
	// endpoint in the appropriate region.
	$host = 'email-smtp.'.AWS_REGION.'.amazonaws.com';
	$port = 587;

	// The subject line of the email
	//$subject = 'Amazon SES test (SMTP interface accessed using PHP)';

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
	    $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

	    // Specify the message recipients.
	    $mail->addAddress($recipient);
	    // You can also add CC, BCC, and additional To recipients here.

	    // Specify the content of the message.
	    $mail->isHTML(true);
	    $mail->Subject    = $subject;
	    $mail->Body       = $bodyHtml;
	    //$mail->AltBody    = $bodyText;
	    $mail->Send();
	    echo "Email sent!" , PHP_EOL;
	} catch (phpmailerException $e) {
	    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
	} catch (Exception $e) {
	    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
	}
}

?>