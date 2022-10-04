<?Php

/**
 * Created by Murad Adygezalov. All Rights Reserved.
 *
 * Date: 20.01.2021
 * Time: 17:30
 */

include_once ("logics/logics.php");


class logs {

	function log($message, $fullname) {

		$logics = new logics();
		$current_date = $logics->getCurrentDate();
		$current_date_time = $logics->getDateForLogging();
		$logMessage = "[".(string)$current_date_time."];$message;$fullname\n";

		file_put_contents('logs/result_log_'.$current_date.'.txt', $logMessage, FILE_APPEND | LOCK_EX);
	}

	function logCustom($message, $fullname) {

        $logics = new logics();
        $current_date = $logics->getCurrentDate();
        $current_date_time = $logics->getDateForLogging();
        $logMessage = "[".(string)$current_date_time."];$message;$fullname\n";

        file_put_contents('logs/custom_log_'.$current_date.'.txt', $logMessage, FILE_APPEND | LOCK_EX);
    }

	function logUpload($message, $email) {

		$logics = new logics();
		$current_date = $logics->getCurrentDate();
		$current_date_time = $logics->getDateForLogging();
		$logMessage = "[".(string)$current_date_time."];$message;$email\n";

		file_put_contents('logs/upload_log_'.$current_date.'.txt', $logMessage, FILE_APPEND | LOCK_EX);
	}

	function logEmptyUser($userId, $firstname, $lastname, $fullname, $form_fullname, $position, $email, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter, $activity) {

		$logics = new logics();
		$current_date = $logics->getCurrentDate();
		$current_date_time = $logics->getDateForLogging();
		$logMessage = "[".(string)$current_date_time."];$userId;$firstname;$lastname;$fullname;$form_fullname;$position;$email;$office_number;$internal_number;$mobile_number;$company_name;$company_id;$boss;$boss_position;$main_holliday_counter;$additional_holliday_counter;$activity\n";

		file_put_contents('logs/empty_user_log_'.$current_date.'.txt', $logMessage, FILE_APPEND | LOCK_EX);
	}

}

?>
