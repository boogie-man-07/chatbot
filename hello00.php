<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';


try {
  // Create the Transport
  $transport = (new Swift_SmtpTransport('kappa.gnhs.ru', 587))
    ->setUsername('personalbot@gnhs.ru')
    ->setPassword('GrHo654123')
  ;

  // Create the Mailer using your created Transport
  $mailer = new Swift_Mailer($transport);

  // Create a message
  $message = (new Swift_Message('Wonderful Subject'))
    ->setFrom(['personalbot@gnhs.ru' => 'Personalbot'])
    ->setTo(['booogie.man.07@gmail.com'])
    ->setBody('Here is the message itself')
    ;

  // Send the message
  $result = $mailer->send($message);
} catch (\Exception $e) {
  echo $e->getMessage();
}




?>
