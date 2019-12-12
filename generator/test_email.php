
<?php
// The message
$message = "Line 1\r\nLine 2\r\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");
$headers = "From: MANUEL CUEVAS <hello@getmodu.com>\r\n";
// Send
print_r(mail('admin@vikingosol.com', 'My Subject', $message, $headers));
print_r(mail('manuel.cuevas@tedesi.com', 'My Subject', $message, $headers));
print_r(mail('trislos@gmail.com', 'My Subject', $message, $headers));
print_r(mail('manuel@bex.io', 'My Subject', $message, $headers));
?>
