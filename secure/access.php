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
        // sql command
        $sql = "SELECT * FROM phonebook WHERE tg_chat_id='".$tg_chat_id."'";
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

    function getUserByPhoneNumber($number) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM phonebook WHERE mobile_number='".$number."' and company_id in ('22','33')";
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
        $sql = "SELECT * FROM phonebook WHERE fullname like '%".$firstname."%' AND fullname like '%".$lastname."%' and company_id in ($ids)";
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
        $sql = "UPDATE phonebook SET is_authorized=?, confirmation_code=? WHERE tg_chat_id='".$tg_chat_id."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ii", $is_authorized, $confirmation_code);

        // launch/execute and store feedback to returnValue
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
        // sql command
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacations SET vacation_type=? WHERE tg_chat_id=?";
            // prepare statement to be executed
            $statement = $this->conn->prepare($sql);

            // error occurred
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind parameters to sql statement
            $statement->bind_param("si", $type, $tg_chat_id);

            // launch/execute and store feedback to returnValue
            $returnValue = $statement->execute();


        } else {

            // SQL command
            $sql = "INSERT INTO vacations SET tg_chat_id=?, vacation_type=?";

            // store query result in $statement
            $statement = $this->conn->prepare($sql);

            // if error
            if (!$statement) {
                throw new Exception($statement->error);
            }

            // bind 5 params of type string to be placed in sql command
            $statement->bind_param("is", $tg_chat_id, $type);
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
            $statement->bind_param("si", $date, $tg_chat_id);

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
            $statement->bind_param("is", $tg_chat_id, $date);
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
            $statement->bind_param("si", $duration, $tg_chat_id);

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
            $statement->bind_param("is", $tg_chat_id, $duration);
            $returnValue = $statement->execute();


        }
        return $returnValue;
    }

    function setRegularVacationAcademicReason($tg_chat_id, $reason) {
        // sql command
        $sql = "SELECT * FROM vacations WHERE tg_chat_id='".$tg_chat_id."'";
        // assign result we got from $sql to result var
        $result = $this->conn->query($sql);

        // if we have at least 1 result returned
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {

            // sql statement
            $sql = "UPDATE vacations SET reason=? WHERE tg_chat_id=?";
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
            $sql = "INSERT INTO vacations SET tg_chat_id=?, reason=?";

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
        $sql = "UPDATE user_vacations SET new_start_date=? WHERE tg_chat_id=? and is_selected=1";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ss", $date, $tg_chat_id);
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

    function getSelectedVacationInfo($tg_chat_id) {
        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM user_vacations WHERE tg_chat_id='$tg_chat_id' and is_selected=1";
        $result = $this->conn->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1 )) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!empty($row)) {
                $returnArray = $row;
            }
        }
        return $returnArray;
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
    function updateEmployeeByEmail($userId, $firstname, $lastname, $fullname, $form_fullname, $position, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $main_holliday_counter, $additional_holliday_counter, $email) {

        // sql statement
        $sql = "UPDATE phonebook SET user_id=?, firstname=?, lastname=?, fullname=?, form_fullname=?, position=?, office_number=?, internal_number=?, mobile_number=?, company_name=?, company_id=?, is_sigma_available=?, is_greenhouse_available=?, is_diall_available=?, boss=?, main_holliday_counter=?, additional_holliday_counter=?, updated_at=CURRENT_TIMESTAMP WHERE email ='".$email."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred 
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ssssssssssiiiisss", $userId, $firstname, $lastname, $fullname, $form_fullname, $position, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $main_holliday_counter, $additional_holliday_counter);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function updateEmployeeByMobileNumber($firstname, $lastname, $fullname, $form_fullname, $position, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $main_holliday_counter, $additional_holliday_counter, $email) {

        // sql statement
        $sql = "UPDATE phonebook SET firstname=?, lastname=?, fullname=?, form_fullname=?, position=?, office_number=?, internal_number=?, company_name=?, company_id=?, is_sigma_available=?, is_greenhouse_available=?, is_diall_available=?, boss=?, main_holliday_counter=?, additional_holliday_counter=?, email=?, updated_at=CURRENT_TIMESTAMP WHERE mobile_number ='".$mobile_number."'";
        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred 
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("ssssssssiiiissss", $firstname, $lastname, $fullname, $form_fullname, $position, $office_number, $internal_number, $company_name, $company_id, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $main_holliday_counter, $additional_holliday_counter, $email);

        // launch/execute and store feedback to returnValue
        $returnValue = $statement->execute();
        return $returnValue;
    }

    function insertEmployee($userId, $lastname, $firstname, $fullname, $form_fullname, $position, $email, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $main_holliday_counter, $additional_holliday_counter) {

        // sql statement 
        $sql = "INSERT INTO phonebook SET user_id=?, lastname=?, firstname=?, fullname=?, form_fullname=?, position=?, email=?, office_number=?, internal_number=?, mobile_number=?, company_name=?, company_id=?, is_sigma_available=?, is_greenhouse_available=?, is_diall_available=?, boss=?, main_holliday_counter=?, additional_holliday_counter=?";

        // prepare statement to be executed
        $statement = $this->conn->prepare($sql);

        // error occurred 
        if (!$statement) {
            throw new Exception($statement->error);
        }

        // bind parameters to sql statement
        $statement->bind_param("sssssssssssiiiisss", $userId, $lastname, $firstname, $fullname, $form_fullname, $position, $email, $office_number, $internal_number, $mobile_number, $company_name, $company_id, $is_sigma_available, $is_greenhouse_available, $is_diall_available, $boss, $main_holliday_counter, $additional_holliday_counter);

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
            $callback_data = $chatID."_".$key;
            $sql = "INSERT INTO user_vacations SET pid=?, tg_chat_id=?, startdate=?, enddate=?, vacation_description=?, amount=?, callback_data=?";
            $statement = $this->conn->prepare($sql);
            if (!$statement) {
                throw new Exception($statement->error);
            }
            $statement->bind_param("sssssss", $data['guid'], $chatID, $value['date1'], $value['date2'], $value['type'], $value['amount'], $callback_data);
            $statement->execute();
        }
    }

    function saveSeparatedUserVacations($chatID, $data) {
        $startDateRaw = strtotime($data['startdate']);
        $startDate = date('d.m.Y', $startDateRaw);
        $endDate = $startDate->modify('+5 days');
        $sql = "INSERT INTO user_vacations SET pid=?, tg_chat_id=?, startdate=?, enddate=?, amount=?, reason=?";
        $statement = $this->conn->prepare($sql);
        if (!$statement) {
            throw new Exception($statement->error);
        }
        $statement->bind_param("ssssss", $data['guid'], $chatID, $data['startdate'], $endDate, $data['amount'], $data['reason']);
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

    function removeEmpoyeeByEmail($email) {

        $sql = "DELETE from phonebook WHERE email='".$email."'";
        $statement = $this->conn->prepare($sql);

        if (!$statement) {
            throw new Exception($statement->error);
        }

        $returnValue = $statement->execute();
        return $returnValue;
    }

    function removeEmpoyeeByMobileNumber($mobile_number) {
        $sql = "DELETE from phonebook WHERE mobile_number='".$mobile_number."'";
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
}
