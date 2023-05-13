<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 05.01.2018
 * Time: 16:36
 */

class email {

function generateUnlockErrorForm() {
    $file = fopen("templates/diallUnlockErrorTemplate.html", "r") or die("Unable to open file");
    $template = fread($file, filesize("templates/diallUnlockErrorTemplate.html"));

    fclose($file);
    return $template;
}

    function generateConfirmationCode($length) {

        //Определяем возможные символы
        $characters = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";

        // Определяем длину кода
        $charactersLength = strlen($characters);

        $confirmationCode = '';
        for ($i = 0; $i < $length; $i++) {
            $confirmationCode .= $characters[rand(0, $charactersLength-1)];
        }

        return $confirmationCode;
    }


    // Формируем шаблон письма для отправки кода подтверждения
    function confirmationTemplate($companyID) {

      switch ($companyID) {
        case 1:
          $file = fopen("templates/sigmaCodeConfirmationTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/sigmaCodeConfirmationTemplate.html"));
          break;
        case 2:
          $file = fopen("templates/greenhouseCodeConfirmationTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/greenhouseCodeConfirmationTemplate.html"));
          break;
        case 3:
          $file = fopen("templates/diallCodeConfirmationTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/diallCodeConfirmationTemplate.html"));
          break;
      }

      fclose($file);
      return $template;
    }

    function generateNewRegularVacationForm($companyID) {
        switch ($companyID) {
            case 1:
              $file = fopen("templates/sigmaRegularVacationFormTemplate.html", "r") or die("Unable to open file");
              $template = fread($file, filesize("templates/sigmaRegularVacationFormTemplate.html"));
              break;
            case 2:
              $file = fopen("templates/greenhouseRegularVacationFormTemplate.html", "r") or die("Unable to open file");
              $template = fread($file, filesize("templates/greenhouseRegularVacationFormTemplate.html"));
              break;
            case 3:
              $file = fopen("templates/diallRegularVacationFormTemplate.html", "r") or die("Unable to open file");
              $template = fread($file, filesize("templates/diallRegularVacationFormTemplate.html"));
              break;
        }

        fclose($file);
        return $template;
    }

    function generateRegularVacationForm($companyID) {
      switch ($companyID) {
        case 1:
          $file = fopen("templates/sigmaRegularVacationFormTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/sigmaRegularVacationFormTemplate.html"));
          break;
        case 2:
          $file = fopen("templates/greenhouseRegularVacationFormTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/greenhouseRegularVacationFormTemplate.html"));
          break;
        case 3:
          $file = fopen("templates/diallRegularVacationFormTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/diallRegularVacationFormTemplate.html"));
          break;
      }

      fclose($file);
      return $template;
    }

    function generatePostponeVacationForm($companyID) {
      switch ($companyID) {
        case 1:
          $file = fopen("templates/sigmaPostponeVacationFormTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/sigmaPostponeVacationFormTemplate.html"));
          break;
        case 2:
          $file = fopen("templates/greenhousePostponeVacationFormTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/greenhousePostponeVacationFormTemplate.html"));
          break;
        case 3:
          $file = fopen("templates/diallPostponeVacationFormTemplate.html", "r") or die("Unable to open file");
          $template = fread($file, filesize("templates/diallPostponeVacationFormTemplate.html"));
          break;
      }

      fclose($file);
      return $template;
    }

    function generateDmsQuestionForm($companyID) {
        switch ($companyID) {
            case 2:
                break;
            case 1; case 3:
              $file = fopen("templates/dmsQuestionFormTemplate.html", "r") or die("Unable to open file");
              $template = fread($file, filesize("templates/dmsQuestionFormTemplate.html"));
              break;
        }
        fclose($file);
        return $template;
    }

    function sendEmail($details) {

        // information of email
        $subject = $details["subject"];
        $to = $details["to"];
        $fromName = $details["fromName"];
        $fromEmail = $details["fromEmail"];
        $body = $details["body"];

        // header required by some of smtp or mail sited
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;content=UTF-8" . "\r\n";
        $headers .= "From: " . $fromName . " <" . $fromEmail . ">" . "\r\n";

        // @mail($email_to, $email_subject, $email_message, $headers)
        if (@mail($to, $subject, $body, $headers)) {
            return true;
        }
    }


    function sendEmailWithAttachment($details, $filePath, $fileName) {

        $attachmentName = $fileName;
        $attachment = $filePath.$fileName;
        $attachmentType = "application/octet-stream";

        $subject = $details["subject"]; // email_subject
        $to = $details["to"]; // email_to
        $fromName = $details["fromName"];
        $fromEmail = $details["fromEmail"]; // email_from
        $body = $details["body"]; // email_message

        $file = fopen($attachment, 'rb');
        $data = fread($file, filesize($attachment));
        fclose($file);

        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        // header required by some of smtp or mail sites
        $headers = "From: " . $fromName . " <" . $fromEmail . ">";
        $headers .= "\nMIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/mixed;\n";
        $headers .= " boundary=\"{$mime_boundary}\"";

        $body .= "This is a multi-part message in MIME format. \n\n" .
        "--{$mime_boundary}\n" .
        "Content-Type:text/html;content=UTF-8\n" .
        "Content-Transfer-Encoding: 8bit\n\n" .
        $body .= "\n\n";

        $data = chunk_split(base64_encode($data));

        $body .= "--{$mime_boundary}\n" .
        "Content-Type: {$attachmentType};\n" .
        " name=\"{$attachmentName}\"\n" .
        "Content-Transfer-Encoding: base64\n\n" .
        $data .= "\n\n" .
        "--{$mime_boundary}--\n";

        // @mail($email_to, $email_subject, $email_message, $headers)
        if (@mail($to, $subject, $body, $headers)) {
            return true;
        }
    }
}
