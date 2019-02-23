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

    public function arrayify($list)
    {
        while ($row = mysqli_fetch_array($list)) {
            $array[] = $row;
        }
        return $array;
    }

    public function returnAllMajors()
    {
        $allMajorQuery = "SELECT major, id FROM category";
        $allMajorsList = $this->connection->query($allMajorQuery);
        $majorArray[] = $this->arrayify($allMajorsList);

        return $majorArray;
    }

    public function returnMajorID($major)
    {
        $majorIDQuery = "SELECT id FROM category WHERE major = '$major'";
        $majorID = $this->connection->query($majorIDQuery);

        $majorID = $this->arrayify($majorID);

        return $majorID[0];
    }

    public function returnMajorReq($majorID)
    {
        $courseIDQuery = "SELECT FK_course FROM appears WHERE categoryID = $majorID AND required = 'true'";
        $courseIDList = $this->connection->query($courseIDQuery);
        $listNum = $courseIDList->num_rows;
        $courseIDArray[] = $this->arrayify($courseIDList);

        $x=0;
        while ($x < $listNum) {
         $courseIDArr[] = $courseIDArray[0][$x][0];
         $x++;
        }

        $peoplesoftIDQuery = "SELECT peoplesoftID FROM course WHERE PK_course = $courseIDArr[0]";
        $peoplesoftIDList = $this->connection->query($peoplesoftIDQuery);
        $peopleSoftIDArray[] = $this->arrayify($peoplesoftIDList);

        return $peoplesoftIDArray;


    }

    public function returnCourses($keyword)
    {
        $keywordQuery = "SELECT title FROM course WHERE title LIKE '%$keyword%'";
        $courseTitleList = $this->connection->query($keywordQuery);

        $courseArray = $this->arrayify($courseTitleList);

        return $courseArray;
    }
}




$db = new Database();
$thing = $db->returnMajorReq("72");
print_r($thing);
