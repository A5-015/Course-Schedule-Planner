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
    // holds important database connection information
    private static $db_host = "localhost";
    private static $db_user = "bsimsekc_nishant";
    private static $db_pass = "reppepreppep";
    private static $db_name = "bsimsekc_test";

    // defines the variable which will eventually be defined
    public $connection;

    //constructor
    public function __construct()
    {
        //assigns the db_connect function to connection so that it can be called anywhere
        $this->connection = $this->db_connect();
    }

    public function db_connect()
    {
        //calls the mysqli_connect and passes all the relevant connection information
        $connection = mysqli_connect(self::$db_host, self::$db_user, self::$db_pass, self::$db_name) or die("Error " . mysqli_error($connection));
        return $connection;
    }

    public function arrayify($list)
    {
        //cleans up how the results from database queries are presented
        while ($row = mysqli_fetch_array($list, MYSQLI_NUM)) {
            $array[] = $row;
        }
        return $array;
    }

    //function that converts a 2d array into a simple, 1D array
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

    public function make1D($array)
    {
        $i=0;
        while ($i < sizeof($array[0])) {
            $temp[$i] = $array[0][$i];
            $i++;
        }
        $oneDArray = $temp;

        return $oneDArray;
    }


    // public function querySet($passedQuery, $passedArray, $returnArray)
    // {
    //     $x =0;
    //     while ($x < sizeof($passedArray)) {
    //         $list = $this->connection->query($passedQuery);
    //         $result = mysqli_fetch_row($list);
    //         array_push($returnArray, $result);
    //         $x++;
    //     }
    //
    //     return $returnArray;
    // }


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
        $majorID = $this->arrayify(sizeof($majorID), $majorID);

        return $majorID;
    }

    public function returnMajorReq($majorID)
    {
        $courseIDQuery = "SELECT title, peoplesoftID FROM course
                          INNER JOIN appears
                          ON appears.FK_course = course.PK_course
                          WHERE categoryID = '$majorID'
                          AND required = 'true'";
        $courseIDList = $this->connection->query($courseIDQuery);

        $courseIDArray = $this->arrayify($courseIDList);

        //
        // $x=0;
        // while ($x < $listNum) {
        //     $courseIDArr[] = $courseIDArray[0][$x][0];
        //     $x++;
        // }
        //
        // $reqArray = [];
        // $x=0;
        // while ($x < sizeof($courseIDArr)) {
        //     $reqQuery = "SELECT title, peoplesoftID FROM course WHERE PK_course = $courseIDArr[$x]";
        //     $reqList = $this->connection->query($reqQuery);
        //     $reqResult = mysqli_fetch_row($reqList);
        //     array_push($reqArray, $reqResult);
        //     $x++;
        // }

        return $courseIDArray;
    }

    // public function returnCourses2($keyword)
    // {
    //     //master list
    //     $courseArray = [];
    //
    //     //gives the courses based on the title search
    //     $keywordQuery = "SELECT title, peoplesoftID FROM course WHERE title LIKE '%$keyword%'";
    //     $courseTitleList = $this->connection->query($keywordQuery);
    //     $resultArray = $this->arrayify($courseTitleList);
    //
    //     $i=0;
    //     while ($i < sizeof($resultArray)) {
    //         array_push($courseArray, $resultArray[$i]);
    //         $i++;
    //     }
    //
    //     //searches for professors using keywords and returns meeting key
    //     $keywordQuery = "SELECT FK_meeting FROM professor WHERE professor LIKE '%$keyword%'";
    //     $meetingList = $this->connection->query($keywordQuery);
    //     $listNum = $meetingList->num_rows;
    //     $meetingArray = $this->arrayify($meetingList);
    //     $meetingArray = $this->fixArray($listNum, $meetingArray);
    //
    //     //searches section key based on provided meeting key
    //     $sectionArray = [];
    //     $j=0;
    //     while ($j < sizeof($meetingArray)) {
    //         $sectionQuery = "SELECT FK_section FROM meeting WHERE PK_meeting = $meetingArray[$j]";
    //         $sectionList = $this->connection->query($sectionQuery);
    //         $sectionResult = mysqli_fetch_row($sectionList);
    //         array_push($sectionArray, $sectionResult);
    //         $j++;
    //     }
    //
    //     $sectionArraySize = sizeof($sectionArray);
    //     $sectionArray = $this->fixArray($sectionArraySize, $sectionArray);
    //
    //     //searches course key based on provided section key
    //     $courseKeyArray = [];
    //     $k=0;
    //     while ($k < $sectionArraySize) {
    //         $courseKeyQuery = "SELECT FK_course FROM section WHERE PK_section = $sectionArray[$k]";
    //         $courseKeyList = $this->connection->query($courseKeyQuery);
    //         $courseKeyResult = mysqli_fetch_row($courseKeyList);
    //         array_push($courseKeyArray, $courseKeyResult);
    //         $k++;
    //     }
    //
    //     $courseKeyArraySize = sizeof($courseKeyArray);
    //     $courseKeyArray = $this->fixArray($courseKeyArraySize, $courseKeyArray);
    //
    //     // (finally...)searches title and peoplesoftID based on provided course key
    //     $l=0;
    //     while ($l < $courseKeyArraySize) {
    //         $courseQuery = "SELECT title, peoplesoftID FROM course WHERE PK_course = $courseKeyArray[$l]";
    //         $courseList = $this->connection->query($courseQuery);
    //         $courseResult = mysqli_fetch_row($courseList);
    //         array_push($courseArray, $courseResult);
    //         $l++;
    //     }
    //
    //
    //     return $courseArray;
    // }

    // public function returnCourses($keyword)
    // {
    //     //instantate the FINAL array as an array
    //     $courseArray = [];
    //
    //     //compact query for KEYWORD, current SEMESTER, current YEAR
    //     $keywordQuery = "SELECT DISTINCT title, peoplesoftID
    //                    FROM course INNER JOIN section
    //                       ON course.PK_course = section.FK_course
    //                    WHERE course.title LIKE '%$keyword%'
    //                    AND section.term LIKE '%Spring%'
    //                    AND section.term LIKE '%2019%'";
    //
    //     //connection and array making code
    //     $courseTitleList = $this->connection->query($keywordQuery);
    //     $courseResultArray = $this->arrayify($courseTitleList);
    //
    //     //adds resulting array to final array iteratively
    //     $i=0;
    //     while ($i < sizeof($courseResultArray)) {
    //         array_push($courseArray, $courseResultArray[$i]);
    //         $i++;
    //     }
    //
    //     $professorQuery = "SELECT DISTINCT section.FK_course
    //                      FROM professor
    //                       INNER JOIN meeting
    //                      		ON professor.FK_meeting = meeting.PK_meeting
    //                       INNER JOIN section
    //                       	ON meeting.FK_section = section.PK_section
    //                       WHERE professor.professor LIKE '%$keyword%'";
    //
    //     $professorList = $this->connection->query($professorQuery);
    //     $professorResultArray = $this->arrayify($professorList);
    //     $professorResultArray = $this->fixArray(sizeof($professorResultArray), $professorResultArray);
    //
    //
    //     $j=0;
    //     while ($j < sizeof($professorResultArray)) {
    //         $professorQuery = "SELECT DISTINCT title, peoplesoftID
    //                        FROM course
    //                        WHERE PK_course = '$professorResultArray[$j]'";
    //
    //         $professorList = $this->connection->query($professorQuery);
    //         $professorResult[] = mysqli_fetch_row($professorList);
    //         array_push($courseArray, $professorResult[$j]);
    //         $j++;
    //     }
    //
    //     return $courseArray;
    // }
    //
    // public function returnAllCourses(){
    //
    // }

    public function returnCourses(bool $returnAll, $keyword)
    {
        //instantate the FINAL array as an array
        $courseArray = [];

        //compact query for KEYWORD, current SEMESTER, current YEAR
        $keywordQuery = "SELECT DISTINCT title, peoplesoftID
                       FROM course INNER JOIN section
                          ON course.PK_course = section.FK_course
                       WHERE section.term LIKE '%Spring%'
                       AND section.term LIKE '%2019%'";
        //AND course.title LIKE '%$keyword%';

        if ($returnAll == false) {
            $keywordQuery = $keywordQuery."AND course.title LIKE '%$keyword%'";

            //connection and array making code
            $courseTitleList = $this->connection->query($keywordQuery);
            $courseResultArray = $this->arrayify($courseTitleList);

            //adds resulting array to final array iteratively
            $i=0;
            while ($i < sizeof($courseResultArray)) {
                array_push($courseArray, $courseResultArray[$i]);
                $i++;
            }

            $professorQuery = "SELECT DISTINCT section.FK_course
                           FROM professor
                            INNER JOIN meeting
                              ON professor.FK_meeting = meeting.PK_meeting
                            INNER JOIN section
                              ON meeting.FK_section = section.PK_section
                            WHERE professor.professor LIKE '%$keyword%'";

            $professorList = $this->connection->query($professorQuery);
            $professorResultArray = $this->arrayify($professorList);
            $professorResultArray = $this->fixArray(sizeof($professorResultArray), $professorResultArray);


            $j=0;
            while ($j < sizeof($professorResultArray)) {
                $professorQuery = "SELECT DISTINCT title, peoplesoftID
                             FROM course
                             WHERE PK_course = '$professorResultArray[$j]'";

                $professorList = $this->connection->query($professorQuery);
                $professorResult[] = mysqli_fetch_row($professorList);
                array_push($courseArray, $professorResult[$j]);
                $j++;
            }
        } else {
            //connection and array making code
            $courseTitleList = $this->connection->query($keywordQuery);
            $courseResultArray = $this->arrayify($courseTitleList);

            //adds resulting array to final array iteratively
            $i=0;
            while ($i < sizeof($courseResultArray)) {
                array_push($courseArray, $courseResultArray[$i]);
                $i++;
            }
        }

        return $courseArray;
    }

    public function returnCoursesWithTime($time)
    {
        $timeQuery = "SELECT DISTINCT title, peoplesoftID FROM section
                          INNER JOIN meeting
                          ON meeting.FK_section = section.PK_section
                          INNER JOIN course
                          ON section.FK_course = course.PK_course
                          WHERE term LIKE '%2019%'
                          AND term LIKE '%Spring%'
                          AND times LIKE '%$time%'";


        $courseTitleList = $this->connection->query($timeQuery);
        $coursesWithoutTime = $this->arrayify($courseTitleList);

        return $coursesWithoutTime;
    }


    public function returnCourseName($peoplesoftIDArray)
    {
        $peoplesoftIDArraySize = sizeof($peoplesoftIDArray);

        $x=0;
        while ($x < $peoplesoftIDArraySize) {
            $courseQuery = "SELECT title FROM course WHERE peoplesoftID = '$peoplesoftIDArray[$x]'";
            $courseResult = $this->connection->query($courseQuery);
            $course = mysqli_fetch_row($courseResult);
            $courseArray[]= $course;
            $x++;
        }

        return $courseArray;
    }

    public function regex($regex)
    {
    }

    public function convertDayToInt($array)
    {
        $i=0;
        while ($i<sizeof($array)) {
            $array[$i] = preg_replace('/U/', 0, $array[$i]);
            $array[$i] = preg_replace('/M/', 1, $array[$i]);
            $array[$i] = preg_replace('/T/', 2, $array[$i]);
            $array[$i] = preg_replace('/W/', 3, $array[$i]);
            $array[$i] = preg_replace('/R/', 4, $array[$i]);
            $i++;
        }

        return $array;
    }

    public function returnCourseTime($peoplesoftID)
    {
        //query
        $timeQuery = "SELECT days, times, title, peoplesoftID, description FROM meeting
                        INNER JOIN section
                        ON meeting.FK_section=section.PK_section
                        INNER JOIN course
                        ON section.FK_course=course.PK_course
                        WHERE section.term LIKE '%Spring%'
                        AND section.term LIKE '%2019%'
                        AND course.peoplesoftID = '$peoplesoftID'";


        $timeInfoList = $this->connection->query($timeQuery);
        $timeInfoArray = $this->arrayify($timeInfoList);
        $timeInfoArray =  $this->make1D($timeInfoArray);

        //if there exists a colon, save what's BEFORE it into $beforeSemiColon
        //and what's AFTER it into $afterSemiColon

        $primaryTimes = [];
        $secondaryTimes = [];


        preg_match_all('/[A-Z]/', $timeInfoArray[0], $matches);
        $days = $this->make1D($matches);
        $days = $this->convertDayToInt($days);
        $i = 0;
        while ($i < sizeof($days)) {
            $primaryTimes["days"][$i] = $days[$i];
            $i++;
        }

        if (preg_match_all('/;/', $timeInfoArray[1])) {
            preg_match_all('/[^;]*/', $timeInfoArray[1], $matches);
            $matches = $this->make1D($matches);
            $beforeSemiColon = $matches[0];
            $afterSemiColon = $matches[2];
        } else {
            $beforeSemiColon=$timeInfoArray[1];
            $afterSemiColon=null;
        }

        preg_match_all('/\d\d(?=:)/', $beforeSemiColon, $matches);
        $matches = $this->make1D($matches);
        $i = 0;
        while ($i < sizeof($days)) {
            $primaryTimes["startHour"][$i] = $matches[0];
            $primaryTimes["endHour"][$i] = $matches[1];
            $i++;
        }

        preg_match_all('/\d\d(?!:)/', $beforeSemiColon, $matches);
        $matches = $this->make1D($matches);
        $i = 0;
        while ($i < sizeof($days)) {
            $primaryTimes["startMinute"][$i] = $matches[0];
            $primaryTimes["endMinute"][$i] = $matches[1];
            $i++;
        }


        //secondarytimes

        if ($afterSemiColon!=null) {
            preg_match_all('/[A-Z]/', $afterSemiColon, $matches);
            $days = $this->make1D($matches);
            $days = $this->convertDayToInt($days);
            $i = 0;
            while ($i < sizeof($days)) {
                $secondaryTimes["days"][$i] = $days[$i];
                $i++;
            }

            preg_match_all('/\d\d(?=:)/', $afterSemiColon, $matches);
            $matches = $this->make1D($matches);
            $i = 0;
            while ($i < sizeof($days)) {
                $secondaryTimes["startHour"][$i] = $matches[0];
                $secondaryTimes["endHour"][$i] = $matches[1];
                $i++;
            }

            preg_match_all('/\d\d(?!:)/', $afterSemiColon, $matches);
            $matches = $this->make1D($matches);
            $i = 0;
            while ($i < sizeof($days)) {
                $secondaryTimes["startMinute"][$i] = $matches[0];
                $secondaryTimes["endMinute"][$i] = $matches[1];
                $i++;
            }
        }

        $mergedTimes = array_merge_recursive($primaryTimes, $secondaryTimes);

        $eventArray = [];

        $i=0;
        while ($i<sizeof($mergedTimes["days"])) {
            $event = ["title"        => $timeInfoArray[2],
                    "peopleSoftID" => $timeInfoArray[3],
                    #"description"  => $timeInfoArray[4],
                    "day"          => $mergedTimes["days"][$i],
                    "startHour"    => $mergedTimes["startHour"][$i],
                    "startMinute"  => $mergedTimes["startMinute"][$i],
                    "endHour"      => $mergedTimes["endHour"][$i],
                    "endMinute"    => $mergedTimes["endMinute"][$i]
                    ];
            $eventArray[] = $event;
            $i++;
        }


        return $eventArray;
    }



    public function __destruct()
    {
    }
}


$db = new Database();
