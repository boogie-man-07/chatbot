<?php


class swiftmailer {

  function sendMailViaSmtp($companyID, $to, $subject, $body) {
    require 'vendor/autoload.php';

    switch ($companyID) {
      case 1:
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
          ->setUsername('info@sigma-capital.ru')
          ->setPassword('aqbnkhrsghffvkvp')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['info@sigma-capital.ru' => 'Bot_PersonalAssistant'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ;
        $mailer->send($message);
        echo 'Message has been sent';
        break;

      case 2:
        $transport = (new Swift_SmtpTransport('kappa.gnhs.ru', 587))
          ->setUsername('personalbot@gnhs.ru')
          ->setPassword('GrHo654123')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['personalbot@gnhs.ru' => 'Bot_PersonalAssistant'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ;
        $mailer->send($message);
        echo 'Message has been sent';
        break;

      case 3:
        $transport = (new Swift_SmtpTransport('mail.diall.ru', 587))
          ->setUsername('personalbot')
          ->setPassword('whWRcG%Y5K')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['personalbot@diall.ru' => 'Bot_PersonalAssistant'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ;
        $mailer->send($message);
        echo 'Message has been sent';
        break;
    }
  }

  function sendRegularVacationMailWithAttachementViaSmtp($companyID, $to, $subject, $body) {
    require 'vendor/autoload.php';

    switch ($companyID) {
      case 1:
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
          ->setUsername('info@sigma-capital.ru')
          ->setPassword('aqbnkhrsghffvkvp')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['info@sigma-capital.ru' => 'Personalbot'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ->attach(Swift_Attachment::fromPath('forms/sigmaRegularVacationForm.docx') ->setFilename('Заявление на отпуск.docx'));
        ;

        $mailer->send($message);
        echo 'Message has been sent';
        if ($mailer) {
          return true;
        }
        break;

      case 2:
        $transport = (new Swift_SmtpTransport('kappa.gnhs.ru', 587))
          ->setUsername('personalbot@gnhs.ru')
          ->setPassword('GrHo654123')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['personalbot@gnhs.ru' => 'Personalbot'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ->attach(Swift_Attachment::fromPath('forms/greenhouseRegularVacationForm.docx') ->setFilename('Заявление на отпуск.docx'));
        ;
        $mailer->send($message);
        echo 'Message has been sent';
        if ($mailer) {
          return true;
        }
        break;

      case 3:
        $transport = (new Swift_SmtpTransport('mail.diall.ru', 587))
          ->setUsername('personalbot')
          ->setPassword('whWRcG%Y5K')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['personalbot@diall.ru' => 'Personalbot'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ->attach(Swift_Attachment::fromPath('forms/diallRegularVacationForm.docx') ->setFilename('Заявление на отпуск.docx'));
        ;
        $mailer->send($message);
        echo 'Message has been sent';
        if ($mailer) {
          return true;
        }
        break;
    }
  }

  function sendPostponedVacationMailWithAttachementViaSmtp($companyID, $to, $subject, $body) {
    require 'vendor/autoload.php';

    switch ($companyID) {
      case 1:
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
          ->setUsername('info@sigma-capital.ru')
          ->setPassword('aqbnkhrsghffvkvp')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['info@sigma-capital.ru' => 'Personalbot'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ->attach(Swift_Attachment::fromPath('forms/sigmaPostponeVacationForm.docx') ->setFilename('Заявление на перенос отпуска.docx'));
        ;

        $mailer->send($message);
        echo 'Message has been sent';
        if ($mailer) {
          return true;
        }
        break;

      case 2:
        $transport = (new Swift_SmtpTransport('kappa.gnhs.ru', 587))
          ->setUsername('personalbot@gnhs.ru')
          ->setPassword('GrHo654123')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['personalbot@gnhs.ru' => 'Personalbot'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ->attach(Swift_Attachment::fromPath('forms/greenhousePostponeVacationForm.docx') ->setFilename('Заявление на перенос отпуска.docx'));
        ;
        $mailer->send($message);
        echo 'Message has been sent';
        if ($mailer) {
          return true;
        }
        break;

      case 3:
        $transport = (new Swift_SmtpTransport('mail.diall.ru', 587))
          ->setUsername('personalbot')
          ->setPassword('whWRcG%Y5K')
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
          ->setFrom(['personalbot@diall.ru' => 'Personalbot'])
          ->setTo([$to])
          ->setBody($body, 'text/html')
          ->attach(Swift_Attachment::fromPath('forms/diallPostponeVacationForm.docx') ->setFilename('Заявление на перенос отпуска.docx'));
        ;
        $mailer->send($message);
        echo 'Message has been sent';
        if ($mailer) {
          return true;
        }
        break;
    }
  }
}


?>
