<?php
/**
 * Created by PhpStorm.
 * User: murad
 * Date: 05.01.2018
 * Time: 15:38
 */

class access {

    var $host = null;
    var $user = null;
    var $pass = null;
    var $name = null;
    var $conn = null;
    var $result = null;
    var $cCode;

    function __construct($dbhost, $dbuser, $dbpass, $dbname) {
        $this->host = $dbhost;
        $this->user = $dbuser;
        $this->pass = $dbpass;
        $this->name = $dbname;
    }


    function connect() {
        // establish connection and store it to conn
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        // if error
        if (mysqli_connect_errno()) {
            echo 'Could not connect to database';
        }

        // support all languages
        $this->conn->set_charset("utf8");
    }

    public function disconnect() {

        if ($this->conn != null) {
            $this->conn->close();
        }
    }

    function getAllUsersFromDb() {
        $returnArray = array();
        //TODO just for now returns only gnhs list of users
        $sql = "SELECT * FROM phonebook where company_id = 2";

        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $statement->execute();
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            $returnArray[] = $row;
        }

        return $returnArray;
    }

    function getListOfAuthorizedUserIds() {
        $returnArray = array();
        $sql = "SELECT tg_chat_id FROM phonebook where tg_chat_id <> ''";

        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $statement->execute();
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            $returnArray[] = $row;
        }

        return $returnArray;
    }

    function getUserByPersonnelNumber($email) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM phonebook WHERE email like '%".$email."%'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function getUserByChatID($tg_chat_id) {

        $returnArray = array();
        //$sql = "SELECT * FROM phonebook WHERE tg_chat_id='".$tg_chat_id."'";
        $sql = "SELECT ph.*, ed.dms_type, ed.is_poll_available FROM phonebook ph left join employee_dms ed ON ph.user_id = ed.user_id WHERE ph.tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function getUserByPhoneNumber($number) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM phonebook WHERE mobile_number='".$number."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function getUserByUserId($userId) {

            $returnArray = array();
            // sql command
            $sql = "SELECT * FROM phonebook WHERE user_id='".$userId."'";
            // assign result we got from $sql to result var
            $result = $this->conn->query($sql);

            // if we have at least 1 result returned
            if ($result != null && (mysqli_num_rows($result) >= 1 )) {

                // assign result we got to $row as associative array
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if (!empty($row)) {
                    $returnArray = $row;
                }
            }

            return $returnArray;
        }

    function getUserByFirstnameAndLastName($firstname, $lastname, $ids) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM phonebook WHERE (firstname = '".$firstname."' AND lastname = '".$lastname."') OR
                (firstname = '".$lastname."' AND lastname = '".$firstname."') and company_id in ($ids)";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    // save email confirmation message's token
    function saveConfirmationCode($confirmation_code, $tg_chat_id, $email) {

        // sql statement
        $sql = "UPDATE phonebook SET confirmation_code=?, confirmation_code_expiration_date=(now() + INTERVAL 5 MINUTE), tg_chat_id=? WHERE email ='".$email."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ss", $confirmation_code, $tg_chat_id);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function updateAuthorizationFlag($is_authorized, $confirmation_code, $tg_chat_id) {

        // sql statement
        $sql = "UPDATE phonebook SET is_authorized=?, confirmation_code=?, authorization_date=CURRENT_TIMESTAMP WHERE tg_chat_id='".$tg_chat_id."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("is", $is_authorized, $confirmation_code);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function updateEmployeeAuthorizationFlag($tg_chat_id, $mobileNumber) {
        $sql = "UPDATE phonebook SET is_authorized=1, tg_chat_id=?, authorization_date=CURRENT_TIMESTAMP WHERE mobile_number ='".$mobileNumber."'";
        $statement = $this->conn->prepare($sql);
        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("s", $tg_chat_id);
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function activateUser($tg_chat_id, $number) {
        $sql = "UPDATE phonebook SET is_authorized=1, tg_chat_id=? where mobile_number=?";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("is", $tg_chat_id, $number);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function removeUserCredentialsByChatID($tg_chat_id) {

        // sql statement
        $sql = "UPDATE phonebook SET is_authorized=0, confirmation_code=NULL, confirmation_code_expiration_date=NULL, tg_chat_id='' WHERE tg_chat_id='".$tg_chat_id."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function removeUserStateByChatID($tg_chat_id) {

        // sql statement
        $sql = "DELETE from dialogs_states_machine WHERE tg_chat_id='".$tg_chat_id."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function getFindUserData($tg_chat_id) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM find_data WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function saveFindUserData($tg_chat_id, $findUserFirstname, $findUserLastname) {

        $sql = "SELECT * FROM find_data WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE find_data SET find_userfirstname=?, find_userlastname=? WHERE tg_chat_id ='".$tg_chat_id."'";
            $statement = $this->conn->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }

            $statement->bind_param("ss", $findUserFirstname, $findUserLastname);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO find_data SET tg_chat_id=?, find_userfirstname=?, find_userlastname=?";
            $statement = $this->conn->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }

            $statement->bind_param("sss", $tg_chat_id, $findUserFirstname, $findUserLastname);
            $returnValue = $statement->execute();

        }

        return $returnValue;
    }

    function removeFindUserDataByChatID($tg_chat_id) {

        // sql statement
        $sql = "DELETE from find_data WHERE tg_chat_id='".$tg_chat_id."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function removeVacationDataByChatID($tg_chat_id) {

        // sql statement
        $sql = "DELETE from separated_user_vacations WHERE tg_chat_id='".$tg_chat_id."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function setState($tg_chat_id, $dialog_state) {

        // sql command
        $sql = "SELECT * FROM dialogs_states_machine WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE dialogs_states_machine SET dialog_state=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("si", $dialog_state, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO dialogs_states_machine SET tg_chat_id=?, dialog_state=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("is", $tg_chat_id, $dialog_state);
            $returnValue = $statement->execute();


        }
        return $returnValue;

    }

    function getState($tg_chat_id) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM dialogs_states_machine WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function setVacationPostponedDate($tg_chat_id, $date) {

        // sql command
        $sql = "SELECT * FROM vacation_form_data WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacation_form_data SET postponed_vacation_start_date=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("si", $date, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacation_form_data SET tg_chat_id=?, postponed_vacation_start_date=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("is", $tg_chat_id, $date);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }


    function setVacationDuration($tg_chat_id, $duration) {

        // sql statement
        $sql = "UPDATE vacation_form_data SET vacation_duration=? WHERE tg_chat_id=?";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ii", $duration, $tg_chat_id);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();

        return $returnValue;
    }


    function setVacationPostponedDuration($tg_chat_id, $duration) {

        // sql statement
        $sql = "UPDATE vacation_form_data SET postponed_vacation_duration=? WHERE tg_chat_id=?";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ii", $duration, $tg_chat_id);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();

        return $returnValue;
    }


    function setVacationBonus($tg_chat_id, $isBonusRequred) {

        // sql statement
        $sql = "UPDATE vacation_form_data SET is_bonus_required=? WHERE tg_chat_id=?";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ii", $isBonusRequred, $tg_chat_id);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();

        return $returnValue;
    }

    function getDataForVacationForm($tg_chat_id) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM vacation_form_data WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function getSeparatePostponedVacationsInfo($tg_chat_id) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM separated_user_vacations WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($returnArray, $row);
        }

        return $returnArray;
    }

    function selectVacationsByUser($tg_chat_id) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";

        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $statement->execute();
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            $returnArray[] = $row;
        }

        return $returnArray;
    }

    // VACATION FUNCTIONS

    function setRegualarVacationType($tg_chat_id, $type) {
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE vacations SET vacation_type=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $type, $tg_chat_id);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO vacations SET tg_chat_id=?, vacation_type=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $tg_chat_id, $type);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setRegularVacationStartDate($tg_chat_id, $date) {

        // sql command
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacations SET vacation_startdate=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("ss", $date, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacations SET tg_chat_id=?, vacation_startdate=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("ss", $tg_chat_id, $date);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }

    function setRegularVacationDuration($tg_chat_id, $duration) {

        // sql command
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacations SET vacation_duration=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("ss", $duration, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacations SET tg_chat_id=?, vacation_duration=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("ss", $tg_chat_id, $duration);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }

    function setPostponedVacationApplicationGroupId($tg_chat_id, $applicationGroupId) {
        $sql = "SELECT * FROM separated_user_vacations WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE separated_user_vacations SET application_group_id=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $applicationGroupId, $tg_chat_id);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO separated_user_vacations SET tg_chat_id=?, application_group_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $tg_chat_id, $applicationGroupId);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setRegularVacationApplicationGroupId($tg_chat_id, $applicationGroupId) {
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE vacations SET application_group_id=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $applicationGroupId, $tg_chat_id);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO vacations SET tg_chat_id=?, application_group_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $tg_chat_id, $applicationGroupId);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setPostponedVacationSigningRequestId($tg_chat_id, $signingRequestId) {
        $sql = "SELECT * FROM separated_user_vacations WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE separated_user_vacations SET signing_request_id=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $signingRequestId, $tg_chat_id);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO separated_user_vacations SET tg_chat_id=?, signing_request_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $tg_chat_id, $signingRequestId);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setRegularVacationSigningRequestId($tg_chat_id, $signingRequestId) {
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE vacations SET signing_request_id=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $signingRequestId, $tg_chat_id);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO vacations SET tg_chat_id=?, signing_request_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $tg_chat_id, $signingRequestId);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setRegularVacationAcademicReason($tg_chat_id, $reason) {
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE vacations SET reason=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $reason, $tg_chat_id);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO vacations SET tg_chat_id=?, reason=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $tg_chat_id, $reason);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setRegularVacationAcademicCause($tg_chat_id, $cause) {
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE vacations SET cause=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $cause, $tg_chat_id);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO vacations SET tg_chat_id=?, cause=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $tg_chat_id, $cause);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setVacationStartDate($tg_chat_id, $date) {

        // sql command
        $sql = "SELECT * FROM vacation_form_data WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacation_form_data SET vacation_start_date=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("si", $date, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacation_form_data SET tg_chat_id=?, vacation_start_date=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("is", $tg_chat_id, $date);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }

    function setVacationEndDate($tg_chat_id, $date) {

        // sql command
        $sql = "SELECT * FROM vacation_form_data WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacation_form_data SET vacation_end_date=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("si", $date, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacation_form_data SET tg_chat_id=?, vacation_end_date=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("is", $tg_chat_id, $date);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }

    function setSelectedVacation($tg_chat_id, $callback_data) {
        $sql = "UPDATE user_vacations SET is_selected=1 WHERE tg_chat_id=? and callback_data=?";
        $statement = $this->conn->prepare($sql);
        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ss", $tg_chat_id, $callback_data);
        $statement->execute();
    }

    function setSelectedVacationNewStartDate($tg_chat_id, $date) {
        $newDate = date('d.m.Y', strtotime($date));
        $sql = "UPDATE user_vacations SET new_start_date=? WHERE tg_chat_id=? and is_selected=1";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ss", $newDate, $tg_chat_id);
        $statement->execute();
    }

    function setSelectedVacationNewDuration($tg_chat_id, $duration) {
        $sql = "UPDATE user_vacations SET new_amount=? WHERE tg_chat_id=? and is_selected=1";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ss", $duration, $tg_chat_id);
        $statement->execute();
    }

    function setSelectedVacationReason($tg_chat_id, $reason) {
        $sql = "UPDATE user_vacations SET reason=? WHERE tg_chat_id=? and is_selected=1";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ss", $reason, $tg_chat_id);
        $statement->execute();
    }

    function setSeparateVacationsReasons($tg_chat_id, $reason) {
        $sql = "UPDATE separated_user_vacations SET reason=? WHERE tg_chat_id=?";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ss", $reason, $tg_chat_id);
        $statement->execute();
    }

    function getSelectedVacationInfo($tg_chat_id) {
        $returnArray = array();
        $sql = "SELECT * FROM user_vacations WHERE is_selected=1 and tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function getLastSeparateVacation($tg_chat_id) {
        $returnArray = array();
        $sql = "SELECT * FROM separated_user_vacations WHERE tg_chat_id='".$tg_chat_id."' order by id desc limit 1";
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function getSumOfVacationParts($tg_chat_id) {
        $sql = "SELECT SUM(AMOUNT) FROM separated_user_vacations WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_row();

            if (!empty($row)) {
                $returnValue = $row[0];
            }
        }

        return $returnValue;
    }

    function setVacationNewStartDate($tg_chat_id, $date) {

        // sql command
        $sql = "SELECT * FROM vacation_form_data WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacation_form_data SET postponed_vacation_start_date=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("si", $date, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacation_form_data SET tg_chat_id=?, postponed_vacation_start_date=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("is", $tg_chat_id, $date);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }

    function setVacationNewEndDate($tg_chat_id, $date) {

        // sql command
        $sql = "SELECT * FROM vacation_form_data WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacation_form_data SET postponed_vacation_end_date=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("si", $date, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacation_form_data SET tg_chat_id=?, postponed_vacation_end_date=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("is", $tg_chat_id, $date);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }

    function setVacationReason($tg_chat_id, $reason) {

        // sql command
        $sql = "SELECT * FROM vacation_form_data WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacation_form_data SET reason=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("si", $reason, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacation_form_data SET tg_chat_id=?, reason=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("is", $tg_chat_id, $reason);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }

    // METHODS FOR SCHEDULLER
    // delete
    function updateEmployeeByEmail($userId, $firstname, $lastname, $fullname, $form_fullname, $form_position, $position, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_employee, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter, $email) {

        // sql statement
        $sql = "UPDATE phonebook SET user_id=?, firstname=?, lastname=?, fullname=?, form_fullname=?, position=?, form_position=?, office_number=?, internal_number=?, mobile_number=?, company_name=?, company_id=?, is_employee=?, is_sigma_available=?, is_greenhouse_available=?, is_diall_available=?, boss=?, boss_position=?, main_holliday_counter=?, additional_holliday_counter=?, updated_at=CURRENT_TIMESTAMP WHERE email ='".$email."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred 
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("sssssssssssiiiiissss", $userId, $firstname, $lastname, $fullname, $form_fullname, $position, $form_position, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_employee, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }
    // delete
    function updateEmployeeByMobileNumber($userId, $firstname, $lastname, $fullname, $form_fullname, $form_position, $position, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_employee, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter, $email) {

        // sql statement
        $sql = "UPDATE phonebook SET user_id=?, firstname=?, lastname=?, fullname=?, form_fullname=?, position=?, form_position=?, office_number=?, internal_number=?, company_name=?, company_id=?, is_employee=?, is_sigma_available=?, is_greenhouse_available=?, is_diall_available=?, boss=?, boss_position=?, main_holliday_counter=?, additional_holliday_counter=?, email=?, updated_at=CURRENT_TIMESTAMP WHERE mobile_number ='".$mobile_number."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred 
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ssssssssssiiiiisssss", $userId, $firstname, $lastname, $fullname, $form_fullname, $position, $form_position, $office_number, $internal_number, $company_name, $company_id, $is_employee, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter, $email);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function updateEmployeeByUserId($physicalId, $firstname, $lastname, $fullname, $form_fullname, $position, $form_position, $email, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_employee, $isRotational, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter, $userId) {

        // sql statement
        $sql = "UPDATE phonebook SET physical_id=?, firstname=?, lastname=?, fullname=?, form_fullname=?, position=?, form_position=?, email=?, office_number=?, internal_number=?, mobile_number=?, company_name=?, company_id=?, is_employee=?, is_rotational=?, is_sigma_available=?, is_greenhouse_available=?, is_diall_available=?, boss=?, boss_position=?, main_holliday_counter=?, additional_holliday_counter=?, updated_at=CURRENT_TIMESTAMP WHERE user_id ='".$userId."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ssssssssssssiiiiiissss", $physicalId, $firstname, $lastname, $fullname, $form_fullname, $position, $form_position, $email, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_employee, $isRotational, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function insertEmployee($userId, $physicalId, $lastname, $firstname, $fullname, $form_fullname, $position, $form_position, $email, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_employee, $isRotational, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter) {

        // sql statement 
        $sql = "INSERT INTO phonebook SET user_id=?, physical_id=?, lastname=?, firstname=?, fullname=?, form_fullname=?, position=?, form_position=?, email=?, office_number=?, internal_number=?, mobile_number=?, company_name=?, company_id=?, is_employee=?, is_rotational=?, is_sigma_available=?, is_greenhouse_available=?, is_diall_available=?, boss=?, boss_position=?, main_holliday_counter=?, additional_holliday_counter=?";

        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred 
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("sssssssssssssiiiiiissss", $userId, $physicalId, $lastname, $firstname, $fullname, $form_fullname, $position, $form_position, $email, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_employee, $isRotational, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $boss_position, $main_holliday_counter, $additional_holliday_counter);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function saveUserVacations($chatID, $data) {
        $sql = "DELETE from user_vacations WHERE tg_chat_id=$chatID";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->execute();

        foreach($data['vacations'] as $key=>$value) {
            $startDate = date('d.m.Y', strtotime($value['date1']));
            $endDate = date('d.m.Y', strtotime($value['date2']));
            $callback_data = $chatID."_".$key;
            $sql = "INSERT INTO user_vacations SET pid=?, tg_chat_id=?, startdate=?, enddate=?, vacation_description=?, amount=?, callback_data=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("sssssss", $data['guid'], $chatID, $startDate, $endDate, $value['type'], $value['amount'], $callback_data);
            $statement->execute();
        }
    }

    function saveSeparatedUserVacations($chatID, $data) {
        $startDateRaw = strtotime($data['new_start_date']);
        $startDate = date('d.m.Y', $startDateRaw);
        $endDateRaw = $data['new_start_date'];
        $amount = (int)$data['new_amount'] - 1;
        $endDate = date('d.m.Y', strtotime($endDateRaw . " +$amount days"));
        $sql = "INSERT INTO separated_user_vacations SET pid=?, tg_chat_id=?, startdate=?, enddate=?, amount=?, reason=?";
        $statement = $this->conn->prepare($sql);
        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ssssss", $data['pid'], $chatID, $startDate, $endDate, $data['new_amount'], $data['reason']);
        $statement->execute();
    }

    function saveSeparatedUserVacationStartDate($chatID, $newStartDate, $data) {
        $sql = "INSERT INTO separated_user_vacations SET pid=?, tg_chat_id=?, startdate=?, reason=?";
        $statement = $this->conn->prepare($sql);
        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ssss", $data['pid'], $chatID, $newStartDate, $data['reason']);
        $statement->execute();
    }

    function saveSeparatedUserVacationDuration($chatID, $amount) {
        $sql = "SELECT startdate FROM separated_user_vacations where enddate is null and tg_chat_id='".$chatID."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_row();

            if (!empty($row)) {
                $startDate = $row[0];
            }
        }

        $realAmount = (int)$amount - 1;
        $endDate = date('d.m.Y', strtotime($startDate . " +$realAmount days"));
        $sql2 = "UPDATE separated_user_vacations SET amount=?, enddate=? where tg_chat_id=? and enddate is null";
        $statement = $this->conn->prepare($sql2);
        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("sss", $amount, $endDate, $chatID);
        $statement->execute();
    }

    function getUserForJobByPhoneNumber($number) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM phonebook WHERE mobile_number='".$number."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }
    // delete
    function removeEmpoyeeByEmail($email) {

        $sql = "DELETE from phonebook WHERE email='".$email."'";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $returnValue = $statement->execute();
        return $returnValue;
    }
    // delete
    function removeEmpoyeeByMobileNumber($mobile_number) {
        $sql = "DELETE from phonebook WHERE mobile_number='".$mobile_number."'";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $returnValue = $statement->execute();
        return $returnValue;
    }

    function removeEmployeeByUserId($userId) {
        $sql = "DELETE from phonebook WHERE user_id='".$userId."'";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $returnValue = $statement->execute();
        return $returnValue;
    }

    function setFeedbackInfo($tg_chat_id, $feedback_text) {

        // sql command
        $sql = "SELECT * FROM feedback WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE feedback SET feedback_text=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }

            $statement->bind_param("ss", $feedback_text, $tg_chat_id);
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO feedback SET tg_chat_id=?, feedback_text=?";
            $statement = $this->conn->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }

            $statement->bind_param("ss", $tg_chat_id, $feedback_text);
            $returnValue = $statement->execute();


        }
        return $returnValue;

    }

    function getFeedbackInfo($tg_chat_id) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM feedback WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function setDmsQuestionInfo($tg_chat_id, $question_text) {
        $sql = "SELECT * FROM dms_question_info WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE dms_question_info SET question_text=? WHERE tg_chat_id=?";
            $statement = $this->conn->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $question_text, $tg_chat_id);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO dms_question_info SET tg_chat_id=?, question_text=?";
            $statement = $this->conn->prepare($sql);

            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $tg_chat_id, $question_text);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function addEmailToDmsQuestionInfo($tg_chat_id, $email) {
        $sql = "UPDATE dms_question_info SET response_email='".$email."' WHERE tg_chat_id='".$tg_chat_id."'";
        $this->conn->query($sql);
    }

    function getDmsQuestionInfo($tg_chat_id) {
        $returnArray = array();
        $sql = "SELECT * FROM dms_question_info WHERE tg_chat_id='".$tg_chat_id."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!empty($row)) {
                $returnArray = $row;
            }
        }
        return $returnArray;
    }

    function removeDmsQuestionInfo($tg_chat_id) {
        $sql = "DELETE FROM dms_question_info WHERE tg_chat_id='".$tg_chat_id."'";
        $this->conn->query($sql);
    }

    function getReguarVacationFormData($tg_chat_id) {
        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // assign result we got to $row as associative array
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }
    //remove from everywhere
    function saveIssuingDocumentData($userId, $documentTypesText) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id ='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET issue_type = 6, type_text = ? WHERE user_id = ?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $documentTypesText, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id = ?, issue_type = 6, type_text = ?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $userId, $documentTypesText);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setIssuingDocumentReferenceType($userId, $referenceType) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET issue_type=5, reference_type=? WHERE user_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("is", $referenceType, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id=?, issue_type=5, reference_type=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("si", $userId, $referenceType);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setIssuingDocumentStartDate($userId, $startDate) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET start_date=? WHERE user_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $startDate, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id=?, start_date=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $userId, $startDate);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setIssuingDocumentEndDate($userId, $endDate) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET end_date=? WHERE user_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $endDate, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id=?, end_date=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $userId, $endDate);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setIssuingDocumentType($userId, $typeText) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET type_text=? WHERE user_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $typeText, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id=?, type_text=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $userId, $typeText);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setIssuingDocumentDeliveryType($userId, $deliveryType) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET delivery_type=? WHERE user_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $deliveryType, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id=?, delivery_type=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $userId, $deliveryType);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setIssuingDocumentDeliveryTypeFreeForm($userId, $deliveryTypeText) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET delivery_type_text=? WHERE user_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $deliveryTypeText, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id=?, delivery_type_text=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $userId, $deliveryTypeText);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setIssuingDocumentApplicationGroupId($userId, $applicationGroupId) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET application_group_id=? WHERE user_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $applicationGroupId, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id=?, application_group_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $userId, $applicationGroupId);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setIssuingDocumentSigningRequestId($userId, $signingRequestId) {
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id='".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE user_issued_document_data SET signing_request_id=? WHERE user_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $signingRequestId, $userId);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO user_issued_document_data SET user_id=?, signing_request_id=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ss", $userId, $signingRequestId);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function getIssuingDocumentData($userId) {
        $returnArray = array();
        $sql = "SELECT * FROM user_issued_document_data WHERE user_id = '".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function getBossPhysicalId($bossFullName) {
        $returnArray = array();
        $sql = "SELECT * FROM phonebook WHERE fullname = '".$bossFullName."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!empty($row)) {
                $returnArray = $row;
            }
        }

        return $returnArray;
    }

    function getApplicationIdsInfo($id) {
        $returnArray = array();
        $appId = $id + 1;
        $sql = "SELECT * FROM application_types WHERE tg_application_id = '".$appId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!empty($row)) {
                $returnArray = $row;
            }
        }
        return $returnArray;
    }

    function getDmsPollInfo($userId) {
        $returnArray = array();
        $sql = "SELECT * FROM polls_user_data WHERE poll_id = 1 and user_id = '".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!empty($row)) {
                $returnArray = $row;
            }
        }
        return $returnArray;
    }

    function setDmsPollInfo($userId, $pollState, $isFinished, $isRelevant) {
        $sql = "INSERT INTO polls_user_data SET poll_id = 1, user_id = ?, poll_state = ?, is_finished = ?, is_relevant = ?";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("siii", $userId, $pollState, $isFinished, $isRelevant);
        $returnValue = $statement->execute();
    }

    function setDmsPollLastState($userId, $lastState) {
        $sql = "UPDATE polls_user_data SET last_state = '".$lastState."' where user_id = '".$userId."'";
        $this->conn->query($sql);
    }

    function disablePollAvailability($userId) {
        $sql = "UPDATE employee_dms SET is_poll_available = 0 where user_id = '".$userId."'";
        $this->conn->query($sql);
    }

    function getDmsPollQuestionsInfo($pollId) {
        $returnArray = array();
        $sql = "SELECT * from polls_reply_options WHERE poll_id = $pollId";
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($returnArray, $row);
        }
        return $returnArray;
    }

    function getDmsUserPollQuestionsInfo($userId, $pollId) {
        $returnArray = array();
        $sql = "SELECT * from polls_user_responses WHERE poll_id = $pollId and user_id='".$userId."'";
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($returnArray, $row);
        }
        return $returnArray;
    }

    function resetPollOptionState($userId, $pollInfo, $pollQuestionInfo) {
        $id = $pollInfo['poll_state'] + 1;
        $pollQuestionData = $pollQuestionInfo[$id];
        $sql = "SELECT * FROM polls_user_responses WHERE user_id='".$userId."' and poll_id='".$pollQuestionData['poll_id']."' and question_id='".$pollQuestionData['question_id']."'";
        $responses = json_decode($pollQuestionData['responses'], true);
        $createdResponsesList = array();
        foreach ($responses['options'] as $key=>$value) {
            array_push($createdResponsesList, $value);
        }
        $createdResponses = json_encode(array('options' => $createdResponsesList));
        $sql = "UPDATE polls_user_responses SET responses = ?, updated = CURRENT_TIMESTAMP where user_id = ? and poll_id = ? and question_id = ?";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ssii", $createdResponses, $userId, $pollQuestionData['poll_id'], $pollQuestionData['question_id']);
        $returnValue = $statement->execute();
    }

    function setSelectedDmsPollOption($userId, $text) {
        $returnArray = array();
        $replyInfo = json_decode($text, true);
        $sql = "SELECT * FROM polls_user_responses WHERE user_id='".$userId."' and poll_id='".$replyInfo['pollId']."' and question_id='".$replyInfo['questionId']."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE polls_user_responses SET responses = ?, updated = CURRENT_TIMESTAMP where user_id = ? and poll_id = ? and question_id = ?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ssii", $replyInfo['selectedReplyId'], $userId, $replyInfo['pollId'], $replyInfo['questionId']);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO polls_user_responses SET user_id = ?, poll_id = ?, question_id = ?, responses = ?, created = CURRENT_TIMESTAMP, updated = CURRENT_TIMESTAMP";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("siis", $userId, $replyInfo['pollId'], $replyInfo['questionId'], $replyInfo['selectedReplyId']);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setSelectedDmsPollOptionForMultipleChoose($userId, $text, $pollQuestionInfo) {
        $returnArray = array();
        $replyInfo = json_decode($text, true);
        $id = $pollInfo['poll_state'];
        $pollQuestionData = $pollQuestionInfo[$id];
//         $pollQuestionData = $pollQuestionInfo[$replyInfo['selectedReplyId'] - 1];
        $sql = "SELECT * FROM polls_user_responses WHERE user_id='".$userId."' and poll_id='".$replyInfo['pollId']."' and question_id='".$replyInfo['questionId']."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!empty($row)) {
                $returnArray = $row;
            }
            $responses = json_decode($returnArray['responses'], true);
            $updatedResponsesList = array();
            foreach ($responses['options'] as $key=>$value) {
                if ($value['id'] != $replyInfo['selectedReplyId']) {
                    array_push($updatedResponsesList, $value);
                } else if ($value['id'] == $replyInfo['selectedReplyId']) {
                    array_push($updatedResponsesList, array(
                        'id' => $value['id'],
                        'title' => $value['title'],
                        'isSelected' => !$value['isSelected']
                    ));
                }
            }
            $updatedResponses = json_encode(array('options' => $updatedResponsesList));
            $sql = "UPDATE polls_user_responses SET responses = ?, updated = CURRENT_TIMESTAMP where user_id = ? and poll_id = ? and question_id = ?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ssii", $updatedResponses, $userId, $replyInfo['pollId'], $replyInfo['questionId']);
            $returnValue = $statement->execute();
        } else {
            $responses = json_decode($pollQuestionData['responses'], true);
            $createdResponsesList = array();
            foreach ($responses['options'] as $key=>$value) {
                if ($value['id'] != $replyInfo['selectedReplyId']) {
                    array_push($createdResponsesList, $value);
                } else if ($value['id'] == $replyInfo['selectedReplyId']) {
                    array_push($createdResponsesList, array(
                        'id' => $value['id'],
                        'title' => $value['title'],
                        'isSelected' => !$value['isSelected']
                    ));
                }
            }
            $createdResponses = json_encode(array('options' => $createdResponsesList));
            $sql = "INSERT INTO polls_user_responses SET user_id = ?, poll_id = ?, question_id = ?, responses = ?, created = CURRENT_TIMESTAMP, updated = CURRENT_TIMESTAMP";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("siis", $userId, $replyInfo['pollId'], $replyInfo['questionId'], $createdResponses);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setSelectedDmsPollOptionForUpdateMultipleChoose($userId, $text, $pollQuestionInfo) {
        $returnArray = array();
        $replyInfo = json_decode($text, true);
        $id = $pollInfo['poll_state'];
        $pollQuestionData = $pollQuestionInfo[$id + 1];
//         $pollQuestionData = $pollQuestionInfo[$replyInfo['selectedReplyId'] - 1];
        $sql = "SELECT * FROM polls_user_responses WHERE user_id='".$userId."' and poll_id='".$replyInfo['pollId']."' and question_id='".$replyInfo['questionId']."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!empty($row)) {
                $returnArray = $row;
            }
            $responses = json_decode($returnArray['responses'], true);
            $updatedResponsesList = array();
            foreach ($responses['options'] as $key=>$value) {
                if ($value['id'] != $replyInfo['selectedReplyId']) {
                    array_push($updatedResponsesList, $value);
                } else if ($value['id'] == $replyInfo['selectedReplyId']) {
                    array_push($updatedResponsesList, array(
                        'id' => $value['id'],
                        'title' => $value['title'],
                        'isSelected' => !$value['isSelected']
                    ));
                }
            }
            $updatedResponses = json_encode(array('options' => $updatedResponsesList));
            $sql = "UPDATE polls_user_responses SET responses = ?, updated = CURRENT_TIMESTAMP where user_id = ? and poll_id = ? and question_id = ?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ssii", $updatedResponses, $userId, $replyInfo['pollId'], $replyInfo['questionId']);
            $returnValue = $statement->execute();
        } else {
            $responses = json_decode($pollQuestionData['responses'], true);
            $createdResponsesList = array();
            foreach ($responses['options'] as $key=>$value) {
                if ($value['id'] != $replyInfo['selectedReplyId']) {
                    array_push($createdResponsesList, $value);
                } else if ($value['id'] == $replyInfo['selectedReplyId']) {
                    array_push($createdResponsesList, array(
                        'id' => $value['id'],
                        'title' => $value['title'],
                        'isSelected' => !$value['isSelected']
                    ));
                }
            }
            $createdResponses = json_encode(array('options' => $createdResponsesList));
            $sql = "INSERT INTO polls_user_responses SET user_id = ?, poll_id = ?, question_id = ?, responses = ?, created = CURRENT_TIMESTAMP, updated = CURRENT_TIMESTAMP";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("siis", $userId, $replyInfo['pollId'], $replyInfo['questionId'], $createdResponses);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

    function setSelectedDmsPollOptionForFreeReply($userId, $text, $pollInfo, $pollQuestionInfo) {
        $returnArray = array();
        $id = $pollInfo['poll_state'];
        $pollQuestionData = $pollQuestionInfo[$id];
        $sql = "SELECT * FROM polls_user_responses WHERE user_id='".$userId."' and poll_id='".$pollQuestionData['poll_id']."' and question_id='".$pollQuestionData['question_id']."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE polls_user_responses SET responses = ?, updated = CURRENT_TIMESTAMP where user_id = ? and poll_id = ? and question_id = ?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("ssii", $text, $userId, $pollQuestionData['poll_id'], $pollQuestionData['question_id']);
            $returnValue = $statement->execute();
        } else {
            $sql = "INSERT INTO polls_user_responses SET user_id = ?, poll_id = ?, question_id = ?, responses = ?, created = CURRENT_TIMESTAMP, updated = CURRENT_TIMESTAMP";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("siis", $userId, $pollQuestionData['poll_id'], $pollQuestionData['question_id'], $text);
            $returnValue = $statement->execute();
        }
        return $returnValue;
    }

//     function getSelectedDmsPollOptionForMultipleChoose($userId, $pollInfo, $pollQuestionInfo) {
//         $returnArray = array();
//         $id = $pollInfo['poll_state'];
//         $pollQuestionData = $pollQuestionInfo[$id];
//         $questionId =
//         $sql = "SELECT * from polls_user_responses WHERE user_id = '".$userId."' and poll_id = '".$pollQuestionData['poll_id']."' and poll_id = '".$pollQuestionData['question_id']."'";
//         $result = $this->conn->query($sql);
//         if ($result != null && (mysqli_num_rows($result) >= 1 )) {
//             $row = $result->fetch_array(MYSQLI_ASSOC);
//             if (!empty($row)) {
//                 array_push($returnArray, $row);
//             }
//         }
//         return $returnArray;
//     }



    function increaseUserDmsPollState($userId, $pollInfo) {
        $nextPollQuestion = $pollInfo['poll_state'] + 1;
        $sql = "UPDATE polls_user_data SET poll_state='".$nextPollQuestion."' WHERE poll_id='".$pollInfo['poll_id']."' and user_id='".$userId."'";
        $result = $this->conn->query($sql);
        return $result;
    }

    function setPollAsFinished($userId, $pollInfo) {
        $sql = "UPDATE polls_user_data SET is_finished = 1 WHERE poll_id='".$pollInfo['poll_id']."' and user_id='".$userId."'";
        $result = $this->conn->query($sql);
        return $result;
    }

    function setCalendarOffset($userId, $offset) {
        $sql = "SELECT * FROM calendar_offset WHERE user_id = '".$userId."'";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $sql = "UPDATE calendar_offset SET offset = '".$offset."' where user_id = '".$userId."'";
            $returnValue = $this->conn->query($sql);
        } else {
            $sql = "INSERT INTO calendar_offset SET user_id = '".$userId."', offset = '".$offset."'";
            $returnValue = $this->conn->query($sql);
        }
        return $returnValue;
    }

    function getCalendarOffset($userId) {
        $returnArray = array();
        $sql = "SELECT * from calendar_offset WHERE user_id = '".$userId."'";
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            array_push($returnArray, $row);
        }
        return $returnArray[0]['offset'];
    }

    function getRotationalWorkersListFromDb() {
        $returnArray = array();
        $sql = "SELECT * FROM phonebook where is_authorized = true and is_rotational = true";

        $statement = $this->conn->prepare($sql);
        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->execute();
        $result = $statement->get_result();

        while ($row = $result->fetch_assoc()) {
            $returnArray[] = $row;
        }

        return $returnArray;
    }
}
