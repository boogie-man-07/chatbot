<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';


try {
  $transport = (new Swift_SmtpTransport('mail.diall.ru', 587))
   ->setUsername('personalbot')
   ->setPassword('whWRcG%Y5K');

  // Create the Mailer using your created Transport
  $mailer = new Swift_Mailer($transport);
  #$smimeSigner = new Swift_Signers_SMimeSigner();
  #$smimeSigner->setSignCertificate('/etc/letsencrypt/live/sigmabot.ddns.net/fullchain.pem', '/etc/letsencrypt/live/sigmabot.ddns.net/privkey.pem');
  #$message->attachSigner($smimeSigner);

  // Create a message
  $message = (new Swift_Message('Wonderful Subject'))
    ->setFrom(['personalbot@diall.ru' => 'Personalbot'])
    ->setTo(['chernuylab@gmail.com'])
    ->setBody('Here is the message itself', 'text/html')
    #->attachSigner($smimeSigner)
  ;

  $result = $mailer->send($message);
} catch (\Exception $e) {
  echo $e->getMessage();
}



?>
