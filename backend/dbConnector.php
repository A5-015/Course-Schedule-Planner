<?php

/*
    Function Instructions:

    0 IS FOR FRONT-END; 1 IS FOR BACK-END

    returnAllMajors():
      *gives you all majors
      *$result[0] will give you the MAJOR and ID of the first row
      *$result[0][0] will give you the MAJOR of the first row

    returnMajorID(): DEPRECATED

    returnMajorReq($majorID):
      *must receive majorID
      *returns all requirements for the given major
      *$result[0] will give you the COURSE and PEOPLESOFTID of the first row
      *$result[0][0] will give you the COURSE of the first row

    returnCourses($keyword):
      *must receive a keyword
      *returns all the courses with the keyword in their title or taught by
      *$result[0] will give you the COURSE and PEOPLESOFTID of the first row
      *$result[0][0] will give you the COURSE of the first row

*/

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
        while ($row = mysqli_fetch_array($list, MYSQLI_NUM)) {
            $array[] = $row;
        }
        return $array;
    }

    public function fixArray($size, $array)
    {
        $i=0;
        while ($i < $size) {
            $temp[] = $array[$i][0];
            $i++;
        }
        $array = $temp;
        unset($temp);

        return $array;
    }

    public function returnAllMajors()
    {
        $allMajorQuery = "SELECT major, id FROM category";
        $allMajorsList = $this->connection->query($allMajorQuery);
        $majorArray[] = $this->arrayify($allMajorsList);

        return $majorArray[0];
    }

    public function returnMajorName($majorID)
    {
        $majorNameQuery = "SELECT major FROM category WHERE id = '$majorID'";
        $majorName = $this->connection->query($majorNameQuery);
        $majorName = $this->arrayify($majorName);
        //$majorName = $this->fixArray(sizeof($majorName),$majorName);

        return $majorName;
    }

    public function returnMajorID($major)
    {
        $majorIDQuery = "SELECT id FROM category WHERE major = '$major'";
        $majorID = $this->connection->query($majorIDQuery);
        $majorID = $this->arrayify(sizeof($majorID),$majorID);

        return $majorID;
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

        $reqArray = [];
        $x=0;
        while ($x < sizeof($courseIDArr)) {
            $reqQuery = "SELECT title, peoplesoftID FROM course WHERE PK_course = $courseIDArr[$x]";
            $reqList = $this->connection->query($reqQuery);
            $reqResult = mysqli_fetch_row($reqList);
            array_push($reqArray, $reqResult);
            $x++;
        }

        return $reqArray;
    }

    public function returnCourses($keyword)
    {
        //master list
        $courseArray = [];

        //gives the courses based on the title search
        $keywordQuery = "SELECT title, peoplesoftID FROM course WHERE title LIKE '%$keyword%'";
        $courseTitleList = $this->connection->query($keywordQuery);
        $resultArray = $this->arrayify($courseTitleList);

        $i=0;
        while ($i < sizeof($resultArray)) {
            array_push($courseArray, $resultArray[$i]);
            $i++;
        }

        //searches for professors using keywords and returns meeting key
        $keywordQuery = "SELECT FK_meeting FROM professor WHERE professor LIKE '%$keyword%'";
        $meetingList = $this->connection->query($keywordQuery);
        $listNum = $meetingList->num_rows;
        $meetingArray = $this->arrayify($meetingList);
        $meetingArray = $this->fixArray($listNum, $meetingArray);

        //searches section key based on provided meeting key
        $sectionArray = [];
        $j=0;
        while ($j < sizeof($meetingArray)) {
            $sectionQuery = "SELECT FK_section FROM meeting WHERE PK_meeting = $meetingArray[$j]";
            $sectionList = $this->connection->query($sectionQuery);
            $sectionResult = mysqli_fetch_row($sectionList);
            array_push($sectionArray, $sectionResult);
            $j++;
        }

        $sectionArraySize = sizeof($sectionArray);
        $sectionArray = $this->fixArray($sectionArraySize, $sectionArray);

        //searches course key based on provided section key
        $courseKeyArray = [];
        $k=0;
        while ($k < $sectionArraySize) {
            $courseKeyQuery = "SELECT FK_course FROM section WHERE PK_section = $sectionArray[$k]";
            $courseKeyList = $this->connection->query($courseKeyQuery);
            $courseKeyResult = mysqli_fetch_row($courseKeyList);
            array_push($courseKeyArray, $courseKeyResult);
            $k++;
        }

        $courseKeyArraySize = sizeof($courseKeyArray);
        $courseKeyArray = $this->fixArray($courseKeyArraySize, $courseKeyArray);

        // (finally...)searches title and peoplesoftID based on provided course key
        $l=0;
        while ($l < $courseKeyArraySize) {
            $courseQuery = "SELECT title, peoplesoftID FROM course WHERE PK_course = $courseKeyArray[$l]";
            $courseList = $this->connection->query($courseQuery);
            $courseResult = mysqli_fetch_row($courseList);
            array_push($courseArray, $courseResult);
            $l++;
        }


        return $courseArray;
    }


    public function __destruct()
    {
    }
}


$db = new Database();
