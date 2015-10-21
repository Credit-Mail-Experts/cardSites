<?php

class database {
    private $host;
    private $username;
    private $password;
    private $database;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function query($queryString) {
        $connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if($connection->connect_errno > 0) {
            die('Unable to connect with Mysql database...');
        }


        $result = $connection->query($queryString);

        $this->lastInsertId = $connection->insert_id;
        
        if(!$result) {
            return false;
        }elseif($result === true) {
            return true;
        }
        
        while($row = $result->fetch_assoc()){
            $return[] = $row;
        }
        
        if(count(@$return) === 1) {
            $obj = new stdClass();
            foreach($return[0] as $key => $value) {
                $obj->$key = $value;  
            }
            return $obj;
        }elseif(sizeof(@$return) >= 2){
            foreach($return as $row) {
                $obj = new stdClass();
                foreach($row as $key => $value) {
                    $obj->$key = $value;
                }
                $returnRows[] = $obj;
            }
            return $returnRows;
        }
    }

    public function runQuery($query) {
        $connection = mysql_connect($this->host, $this->username, $this->password);

        if (!$connection) {
            die('Could not connect: ' . mysql_error());
        }

        mysql_select_db($this->database, $connection);

        $result = mysql_query($query);
        return $result;

        mysql_close($connection);
    }

    public function dbPrepare($string, $case = "default") {
        $string = mysql_real_escape_string($string);

        if (empty($string)) {
            $string = "NULL";
        } else {
            switch ($case) {
                case "upper":
                    $string = strtoupper($string);
                    break;
                case "lower":
                    $string = strtolower($string);
                    break;
                case "ucfirst":
                    $string = strtolower($string);
                    $string = ucfirst($string);
                    break;
                case "lcfirst":
                    $string = lcfirst($string);
                    break;
                case "ucwords":
                    $string = strtolower($string);
                    $string = ucwords($string);
                    break;
                default:
                    break;
            }
            $string = "'" . $string . "'";
        }

        return $string;
    }

    function checkEmpty($string) {
        $string = mysql_real_escape_string($string);

        if (empty($string)) {
            $string = "'%'";
            return $string;
        } else {
            $string = "'$string'";
            return $string;
        }
    }
}

function stripQuotes($string) {
    $string = str_replace("'", "", $string);

    if ($string == "NULL") {
        $string = "";
    }
    return $string;
}

function upcfirst($string) {
    $string = strtolower($string);
    $string = ucfirst($string);

    return $string;
}

function upcwords($string) {
    $string = strtolower($string);
    $string = ucwords($string);

    return $string;
}

function getTimeDifference($start, $finish) {
    return ceil(abs((strtotime($finish) - strtotime($start)) / 60));
}

// https://stackoverflow.com/questions/3903317/how-can-i-make-an-array-of-times-with-half-hour-intervals
function getIncrementedTimes($lower = 0, $upper = 86400, $step = 3600, $format = '') {
    $times = array();

    if (empty($format)) {
        $format = 'g:i a';
    }

    foreach (range($lower, $upper, $step) as $increment) {
        $increment = gmdate('H:i', $increment);

        list( $hour, $minutes ) = explode(':', $increment);

        $date = new DateTime($hour . ':' . $minutes);

        $times[(string) $increment] = $date->format($format);
    }

    return $times;
}
?>