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
    // holds important database connection information; it is made private
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

    //used to return sql queries in a readable, usable format
    public function arrayify($list)
    {
        //cleans up how the results from database queries are presented
        while ($row = mysqli_fetch_array($list, MYSQLI_NUM)) {
            $array[] = $row;
        }
        return $array;
    }

    //function that converts a 2d array into a 1D array by grabbing the first index of the 2D array
    //formerly known as fixArray
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

    //function that converts an unnecessarily nested 2d array into a 1D array
    //formerly knonwn as make1D
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

    //function that returns all majors
    public function returnAllMajors()
    {
        $allMajorQuery = "SELECT major, id FROM category";
        $allMajorsList = $this->connection->query($allMajorQuery);
        $majorArray[] = $this->arrayify($allMajorsList);

        return $majorArray[0];
    }

    //function that returns the major name given the internal reference ID for the major
    public function returnMajorName($majorID)
    {
        $majorNameQuery = "SELECT major FROM category WHERE id = '$majorID'";
        $majorName = $this->connection->query($majorNameQuery);
        $majorName = $this->arrayify($majorName);

        return $majorName;
    }

    //DEPRECATED FUNCTION;
    // public function returnMajorID($major)
    // {
    //     $majorIDQuery = "SELECT id FROM category WHERE major = '$major'";
    //     $majorID = $this->connection->query($majorIDQuery);
    //     $majorID = $this->arrayify(sizeof($majorID), $majorID);
    //
    //     return $majorID;
    // }

    //function that returns all the major requirements given the internal reference ID of the major
    public function returnMajorReq($majorID)
    {
        //SQL query relating apperars and course tables together to obtain the
        //title and peopleSoftID of the major specific required courses
        $reqQuery = "SELECT title, peoplesoftID FROM course
                          INNER JOIN appears
                          ON appears.FK_course = course.PK_course
                          WHERE categoryID = '$majorID'
                          AND required = 'true'";
        $reqList = $this->connection->query($reqQuery);
        $reqArray = $this->arrayify($reqList);

        return $reqArray;
    }

    //function that returnsCourses based on whether all or only keyword related
    //are needed and whether or not the duplicate courses are needed:
    //example 1: returnCourses(true, "null", "null");
    //example 2: returnCourses(false, true, "object-oriented");
    //example 3: returnCourses(false, false, "object-oriented");
    public function returnCourses(bool $returnAll, bool $distinct, $keyword)
    {
        //instantate the FINAL array as an array
        $courseArray = [];

        //compact query for KEYWORD, current SEMESTER, current YEAR
        $keywordQuery = "SELECT DISTINCT title, peoplesoftID
                       FROM course INNER JOIN section
                          ON course.PK_course = section.FK_course
                       WHERE section.term LIKE '%Spring%'
                       AND section.term LIKE '%2019%'";

        //in the case that query is keyword specific, then append the keyword
        //to the SQL query.
        if ($returnAll == false) {
            if ($distinct == true) {
                $keywordQuery = $keywordQuery."AND course.title LIKE '%$keyword%'";
            } elseif ($distinct == false) {
                $keywordQuery = "SELECT title, peoplesoftID
                             FROM course INNER JOIN section
                                ON course.PK_course = section.FK_course
                             WHERE section.term LIKE '%Spring%'
                             AND section.term LIKE '%2019%'
                             AND course.title LIKE '%$keyword%'";
            }
            //connection and array making
            $courseTitleList = $this->connection->query($keywordQuery);
            $courseResultArray = $this->arrayify($courseTitleList);

            //adds resulting array to final array iteratively
            $i=0;
            while ($i < sizeof($courseResultArray)) {
                array_push($courseArray, $courseResultArray[$i]);
                $i++;
            }

            //part one of query to return courses taught by professor put as keyword
            //split into two parts as SQL engine would return errors
            //this query only returns the foreign key of the final table "course"

            // if ($distinct == true) {
            //     $professorQuery = "SELECT DISTINCT section.FK_course
            //                  FROM professor
            //                   INNER JOIN meeting
            //                     ON professor.FK_meeting = meeting.PK_meeting
            //                   INNER JOIN section
            //                     ON meeting.FK_section = section.PK_section
            //                   WHERE professor.professor LIKE '%$keyword%'";
            // } elseif ($distinct == false) {
                $professorQuery = "SELECT DISTINCT section.FK_course
                             FROM professor
                              INNER JOIN meeting
                                ON professor.FK_meeting = meeting.PK_meeting
                              INNER JOIN section
                                ON meeting.FK_section = section.PK_section
                              WHERE professor.professor LIKE '%$keyword%'
                              AND section.term LIKE '%Spring%'
                              AND section.term LIKE '%2019%'";
            // }

            //query and array making and formatting of results
                $professorList = $this->connection->query($professorQuery);
                $professorResultArray = $this->arrayify($professorList);
                $professorResultArray = $this->fixArray(sizeof($professorResultArray), $professorResultArray);

            //iteratively search for course title and peopleSoftID from the
            //foreign key array
            $j=0;
            while ($j < sizeof($professorResultArray)) {
                if ($distinct == true) {
                    $professorQuery = "SELECT DISTINCT title, peoplesoftID
                                    FROM course
                                    WHERE PK_course = '$professorResultArray[$j]'";
                } elseif ($distinct == false) {
                    $professorQuery = "SELECT title, peoplesoftID
                                    FROM course
                                    WHERE PK_course = '$professorResultArray[$j]'";
                }

                //query and array making and formatting of results
                $professorList = $this->connection->query($professorQuery);
                $professorResult[] = mysqli_fetch_row($professorList);
                //pushes results to the final array
                array_push($courseArray, $professorResult[$j]);
                $j++;
            }

            //if the argument is TRUE, use the original query which
          //does not include a keyword, thus showing all the current semester courses
        } else {
            //query and array making and formatting of results
            $courseTitleList = $this->connection->query($keywordQuery);
            $courseResultArray = $this->arrayify($courseTitleList);

            //adds resulting array to final array iteratively
            $i=0;
            while ($i < sizeof($courseResultArray)) {
                array_push($courseArray, $courseResultArray[$i]);
                $i++;
            }
        }
        //returns the early on instantated array
        return $courseArray;
    }

    //function that returns all courses given a class time in the 24 hour format
    //eg: returnCoursesWithTime("09:00")
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
        $coursesWithTime = $this->arrayify($courseTitleList);

        return $coursesWithTime;
    }


    //function that returns the course name given the courseID name (aka peopleSoftID)
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

    //function that converts su
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

    public function courseTimeQuery($peoplesoftID)
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

      $i=0;
      while($i<sizeof($timeInfoArray))
      {
        $eventArray [] = $this->returnCourseTime(array_slice($timeInfoArray, $i, 1));
        $i++;
      }

      $i=1;
      while($i<sizeof($eventArray))
      {
        $j=0;
        while($j<sizeof($eventArray[$i]))
        {
          $eventArray[0][] = $eventArray[$i][$j];
          unset($eventArray[$i][$j]);
          $j++;
        }
      $i++;
      }

      $eventArray = $this->make1D($eventArray);
      return $eventArray;

    }

    public function returnCourseTime($timeInfoArray)
    {
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
                    "description"  => $timeInfoArray[4],
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
