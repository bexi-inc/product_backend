<?php
//include "http://generator.getmodu.com/api/v1/emails.php"; //include email sender
header("Access-Control-Allow-Origin: *");

include "../config.php";
include( __DIR__.'/../api/v1/emails.php');

echo ( __DIR__);
function get_html(){
    $numItems = count($_POST);
    $i = 0;
    $string='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Modu - Form Submission</title><!--[if (gte mso 9)|(IE)|(mso 16)|(mso 7)|(mso 10)|(mso 13) |(mso 19)]><style type="text/css"> table{border-collapse: collapse;}a{text-decoration: none; color:#fff;}body, table, td, tr, h1, p, a{font-family: Arial, Helvetica, sans-serif !important;}</style><![endif]--> <style>body{margin: 0 !important; padding: 0; background-color: #ffffff;}table{border-spacing: 0; font-family: sans-serif; color: #333333;}img{border: 0;}div[style*="margin: 16px 0"]{margin:0 !important;}.wrapper{width: 100%; table-layout: fixed; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}.webkit{max-width: 480px; margin: 0 auto;}.outer{Margin: 0 auto; width: 100%; max-width: 480px;}.inner{padding: 10px;}.contents{width: 100%;}p{Margin: 0;}.full-width-image img{width: 100%; max-width: 480px; height: auto;}/* One column layout */ .one-column .contents{text-align: left;}.one-column p{font-size: 14px; Margin-bottom: 10px;}/*Two column layout*/ .two-column{text-align: center; font-size: 0;}.two-column .column{width: 100%; max-width: 240px; display: inline-block; vertical-align: top;}.two-column .contents{font-size: 14px; text-align: left;}.two-column .text{padding-top: 10px;}.email-title{font-family: Arial ,sans-serif; font-weight: 500;}.email-text, .email-subTitle{font-family: Arial,sans-serif;}.email-text{font-weight: 200;}.email-subTitle{font-weight: 700;}/*Media Queries*/ @media only screen and (max-width: 460px){.two-column .column{max-width: 100% !important;}.two-column img{max-width: 100% !important;}.emailButton{max-width:460px !important; width:100% !important;}.emailButton a{display:block !important; font-size:16px !important;}.columnImage{height:auto !important; max-width:480px !important; width:100% !important;}.lineDivider{display: none !important;}}@media screen and (min-width: 401px){.two-column .column{max-width: 50% !important;}}</style></head><body style="padding: 0;background-color: #ffffff;margin: 0 !important;"><center class="wrapper" style="width: 100%;table-layout: fixed;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;"><div class="webkit" style="max-width: 480px;margin: 0 auto;"><!--[if (gte mso 9)|(IE)]><table width="480" align="center"><tr><td><![endif]--><table class="outer" align="center" role="presentation" style="border-spacing: 0;font-family: sans-serif;color: #333333;margin: 0 auto;width: 100%;max-width: 480px;"> <tr> <td colspan="0" height="64"></td></tr><tr><td class="inner" style="padding: 10px;"> <img src="http://uploads.getmodu.com/emails/modu-beta-logo.png" alt="Modu Logo" width="143px" height="25px" style="margin-top: 20px;border: 0;"></td></tr><tr><td class="one-column"><table width="100%" role="content" style="border-spacing: 0;font-family: sans-serif;color: #333333;"><tr><td class="inner contents" style="padding: 10px;width: 100%;text-align: left;"> <h1 style="color: #000000;font-size: 36px;line-height: 48px;font-family: Arial ,sans-serif;font-weight: 500;" class="email-title">You&#39ve got mail!</h1> <p style="color: #7F8FA4;opacity: 0.9;font-size: 16px;line-height: 24px;margin: 0;font-family: Arial,sans-serif;font-weight: 200;margin-bottom: 10px;" class="email-text">Your project ';
    $string.=$_POST["project_name"];
    $string.=' has a new form submission!</p></td></tr></table></td></tr>';
    foreach ( $_POST as $key => $value )
    {
        $key=str_replace("_"," ",$key);
        if(++$i <= $numItems-2) {
            if($key==="Email"){
                $string.='<tr><td class="one-column"><table width="100%" role="content" style="border-spacing: 0;font-family: sans-serif;color: #000000;"><tr><td class="inner contents" style="padding: 10px;width: 100%;text-align: left;"> <p style="color: #000000;opacity: 0.9;font-size: 16px;line-height: 24px;margin: 0;font-family: Arial,sans-serif;font-weight: 700;margin-bottom: 10px;" class="email-text">';
                $string.=$key;
                $string.='</p><a href="mailto:'.$value.'" style="color: #7F8FA4;opacity: 0.9;font-size: 16px;line-height: 24px;margin: 0;font-family: Arial,sans-serif;font-weight: 200;margin-bottom: 10px;" class="email-text">';
                $string.=$value;
                $string.='</a></td></tr></table></td></tr>';
            }else{
                $string.='<tr><td class="one-column"><table width="100%" role="content" style="border-spacing: 0;font-family: sans-serif;color: #000000;"><tr><td class="inner contents" style="padding: 10px;width: 100%;text-align: left;"> <p style="color: #000000;opacity: 0.9;font-size: 16px;line-height: 24px;margin: 0;font-family: Arial,sans-serif;font-weight: 700;margin-bottom: 10px;" class="email-text">';
                $string.=$key;
                $string.='</p><p style="color: #7F8FA4;opacity: 0.9;font-size: 16px;line-height: 24px;margin: 0;font-family: Arial,sans-serif;font-weight: 200;margin-bottom: 10px;" class="email-text">';
                $string.=$value;
                $string.='</p></td></tr></table></td></tr>';
            }
        }
    }
    $string.='<tr><td class="one-column"><table width="100%" role="presentation" style="border-spacing: 0;font-family: sans-serif;color: #333333;"> <tr> <td colspan="0" height="70"></td></tr><tr><td class="inner contents" style="padding: 10px;width: 100%;text-align: left;"> <p style="color: #B8C7DC;font-size: 12px;margin: 0;font-family: Arial,sans-serif;font-weight: 200;" class="email-text"> © 2019 Bexi, Inc. All rights reserved. 101 Estudillo Avenue, San Leandro, CA 94577</p><a href="http://www.bexi.io" target="_blank" style="color: #2DD3D6;font-size: 12px;text-decoration: none;font-weight: bold;font-family: Arial,sans-serif;" class="email-text">www.bexi.io</a> </td></tr></table> </td></tr></table><!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]--></div></center></body></html>';
    return $string;
}


	$to = $_POST["email_to"]; //send email to
    $subject = "Form Project- ".$_POST["project_name"];
    $message = get_html();
    $from = "noreply@getmodu.com";
    $headers = "From: GetModu <hello@getmodu.com>\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    
    SendEmailForm($to, $subject, $message);
?>