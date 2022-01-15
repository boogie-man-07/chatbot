<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';


try {
  // Create the Transport
  $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
    ->setUsername('info@sigma-capital.ru')
    ->setPassword('aqbnkhrsghffvkvp')
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
