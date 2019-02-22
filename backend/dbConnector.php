<?php

class Database
{
    private static $db_host = "localhost";
    private static $db_user = "bsimsekc_nishant";
    private static $db_pass = "reppepreppep";
    private static $db_name = "bsimsekc_test";

    public $connection;

    public function __construct()
    {
        $this->connection = $this->db_connect();
    }

    public function db_connect()
    {
        $connection = mysqli_connect(self::$db_host, self::$db_user, self::$db_pass, self::$db_name) or die("Error " . mysqli_error($connection));
        return $connection;
    }

    public function arrayify($list, $header)
    {
        $i = 0;
        while ($row = mysqli_fetch_array($list)) {
            $j = 0;
            while ($j == sizeof($header)) {
                $array[$i][$j] = $row[$header[$j]];
                echo $array[$i][$j];
                $j++;
            }
            $i++;
        }

        return $array;
    }

    public function returnAllMajors()
    {
        $allMajorQuery = "SELECT major, id FROM category";
        $allMajorsList = $this->connection->query($allMajorQuery);
        $majorArray = $this->arrayify($allMajorsList, $header);

        $header = ["major", "id"];

        return $majorArray;
    }

    public function returnMajorID($major)
    {
        $majorIDQuery = "SELECT id FROM category WHERE major = '$major'";
        $majorID = $this->connection->query($majorIDQuery);

        $header = ["major"];

        $majorID = $this->arrayify($majorID, $header);

        return $majorID[0];
    }

    public function returnCourses($keyword)
    {
        $keywordQuery = "SELECT title FROM course WHERE title LIKE '%$keyword%'";
        $courseTitleList = $this->connection->query($keywordQuery);

        $courseArray = $this->arrayify($courseTitleList, "title");

        return $courseArray;
    }
}




$db = new Database();
print_r($db->returnallMajors());
