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

    function getUserByFirstnameAndLastName($firstname, $lastname) {

        $returnArray = array();
        // sql command
        $sql = "SELECT * FROM phonebook WHERE fullname like '%".$firstname."%' AND fullname like '%".$lastname."%'";
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
}
