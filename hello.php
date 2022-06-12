<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';


try {
  // Create the Transport
  $transport = (new Swift_SmtpTransport('smtp.office365.com', 587, 'tls'))
    ->setUsername('info@sigma-capital.ru')
    ->setPassword('F89%a*6y')
  ;

  // Create the Mailer using your created Transport
  $mailer = new Swift_Mailer($transport);

  // Create a message
  $message = (new Swift_Message('Wonderful Subject'))
    ->setFrom(['info@sigma-capital.ru' => 'Personalbot'])
    ->setTo(['chernuylab@gmail.com'])
    ->setBody('Here is the message itself')
    ;

  $message->attach(Swift_Attachment::fromPath('forms/sigmaRegularVacationForm.docx'));

  // Send the message
  $result = $mailer->send($message);
} catch (\Exception $e) {
  echo $e->getMessage();
}


?>
