<?php
require("class.phpmailer.php");

$mail = new PHPMailer();
  
$mail->IsSMTP();  // telling the class to use SMTP
// $mail->Host       = 'smtp.gmail.com:587'.$eol;  // SMTP server
// $mail->Host = 'localhost';
// $mail->From     = "dhruten108@gmail.com";
// $mail->AddAddress("dhruten.dhanani@ecosmob.com","malay.patel@ecosmob.com");
// echo "dhruten";
// $mail->Subject  = "Have A Good Day";
// $mail->Body     = "Hi! \n\n This is a Greetings.";
$mail->Body = "<b>Hi, your first SMTP mail via gmail server has been received. Great Job!.. <br/><br/>by <a href='http://www.google.com'>Good Luck</a></b>";
// $mail->WordWrap = 50;
// $smtp->html_debug=1; 
// $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
// $mail->SMTPAuth   = true;                  // enable SMTP authentication
// $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
$mail->Host       = "localhost";      // sets GMAIL as the SMTP server
$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
// $mail->Username   = "venus.cabin@gmail.com";  
// $mail->Password   = "zxsder45";          
$mail->SetFrom('nirali.soni@ecosmob.com', 'Hi There');
$mail->AddReplyTo("nirali.soni@ecosmob.com","Hi There");
$mail->Subject    = "Good bye";
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
$address = "12345@ecosmob.wwtelecom.net";
// $address1= "aniruddh.bhilvare@ecosmob.com";
// $email="suresh.talasaniya@ecosmob.com";
// $mail->AddCC(trim($email));
$mail->AddAddress($address, "Malay Patel");
// $mail->AddAddress($address1, "Malay Patel");
$mail->AddAttachment("/home/dhruten/Downloads/CCITT_1.TIF.pdf");
// $mail->AddAttachment("rates_off.png");

if(!$mail->Send()) {
  echo 'Message sending Failed.';
  echo 'Mailer error: ' . $mail->ErrorInfo;
//   echo "<pre>";print_r($mail);
} else {
  echo 'Message has been sent.';
}
?>