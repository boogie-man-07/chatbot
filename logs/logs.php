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
		$logMessage = "[".(string)$current_date_time."] ".$message."\n";

		file_put_contents('logs/result_log_'.$current_date.'.txt', $logMessage." - ".$fullname, FILE_APPEND | LOCK_EX);
	}

}

?>
