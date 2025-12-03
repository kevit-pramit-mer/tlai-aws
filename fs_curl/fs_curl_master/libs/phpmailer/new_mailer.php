<?php
require("class.phpmailer.php");

if(isset($_POST))
{
    $mail = new PHPMailer();
    $eol = PHP_EOL;     
    $mail->IsSMTP();  // telling the class to use SMTP
    // $mail->Host       = 'smtp.gmail.com:587'.$eol;  // SMTP server
    // $mail->Host = 'localhost';
    // $mail->From     = "dhruten108@gmail.com";
    // $mail->AddAddress("dhruten.dhanani@ecosmob.com","malay.patel@ecosmob.com");
    // echo "dhruten";
    // $mail->Subject  = "Have A Good Day";
    $mail->Body     = "Hi! \n\n This is a Greetings.";
    $mail->WordWrap = 50;

    // $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "dhruten108@gmail.com";  // GMAIL username
    $mail->Password   = "nfwpkdmygmail";  // GMAIL password
    $from=$_POST['from_email'];
    $mail->SetFrom($from,'First Mail');
    $mail->AddReplyTo($from,"First Mail");
    $mail->Subject    = "This mail is sent ny SMTP mailer";
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
    $address=$_POST['to_email'];
    $mail->AddAddress($address, "Dhruten Dhanani");
    // $mail->AddAttachment("rightedge.png");
//     $mail->AddAttachment("rates_off.png");
}
?>
<html>
<body>
<form method="POST" action="mailer.php">
From
<input type="text" name="from_email"><br>
To
<input type="text" name="to_email"><br>
<input type="submit" name="sub_mail" value="Send Mail"><br>
<?
 if(!$mail->Send()) {
      echo "\n"."Message sending Failed."."\n\n";
      echo "\n".'Mailer error: ' . $mail->ErrorInfo;
    //   echo "<pre>";print_r($mail);
    } else {
      echo "\n".'Message has been sent.';
    }
?>
</form>
</body>
</html>