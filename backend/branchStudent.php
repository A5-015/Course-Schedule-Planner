<?php
require_once "dbConnector.php";
//completedReqs needs to be in [0][0]

/*
// Times are in 24 hour format
$startTimeHour = 9;
$startTimeMinute = 0;
$endTimeHour = 10;
$endTimeMinute = 0;

$title = "Anthropology and the Arab World";
$description = "ADADSA";

// Days ofset from Sunday
$dayOfTheWeek = 0;
*/


/*

// creat it if it doesn't exist
if(!isset($_SESSION["selectedCourses"])){
  $_SESSION["selectedCourses"] = [];
}

$thing = $student -> db -> returnCourseTime($query);

$session = $_SESSION["selectedCourses"];
$session[] = $thing;
$_SESSION["selectedCourses"] = $session;


*/

class Student
{
    public $major=[];
    public $majorID=[];
    public $completedReqs=[];
    public $constraints=[
      "9AM"   => 0,
      "PE"    => 0,
      "CCOL"  => 0,
      "CDAD"  => 0,
      "CCEA"  => 0,
      "CADT"  => 0,
      "CSTS"  => 0,
      "FYWS"  => 0,
    ];

    public $selectedCourses=[];

    public function __construct()
    {
        $this->db = new Database();
    }

    public function setMajor($initMajorID)
    {
        $this->majorID = $initMajorID;
        $this->major = $this->db->returnMajorName($initMajorID);
    }

    public function shareMajorReqs($peoplesoftID)
    {
        // $x=0;
        // $idExists = false;
        // while ($x<sizeof($this->completedReqs)) {
        //     if ($this->completedReqs[$x] == $peoplesoftID) {
        //         $idExists = true;
        //     }
        //     $x++;
        // }
        // if ($idExists == false) {
        //     $this->completedReqs[] = $peoplesoftID;
        // }

        $reqSearch = array_search($peoplesoftID, $this->completedReqs, true);
        if ($reqSearch === false) {
            $this->completedReqs[] = $peoplesoftID;


        // $temp = [$courseName, $peoplesoftID];
            // $this->completedReqs[]= $temp;
        } else {
            unset($this->completedReqs[$reqSearch]);
            $this->completedReqs = array_values($this->completedReqs);
        }
    }

    public function filterByTitle($allCourses, $title)
    {
        $i=0;
        $offsetArray =[];
        if ($this->constraints["$title"] =="1") {
            while ($i < sizeof($allCourses)) {
                if (preg_match("/$title/", $allCourses[$i][1])) {
                    array_push($offsetArray, $i);
                }

                $i++;
            }

            $i=0;
            while ($i < sizeof($offsetArray)) {
                unset($allCourses[$offsetArray[$i]]);
                $i++;
            }
        }

        return $allCourses;
    }

    public function filterRequirements($allCourses, $completedReqs)
    {
        $i=0;
        while ($i < sizeof($allCourses)) {
            if (in_array($allCourses[$i][1], $completedReqs)) {
                unset($allCourses[$i]);
            }
            $i++;
        }

        return $allCourses;
    }


    public function compareArrayMulti($arraya, $arrayb)
    {
        foreach ($arraya as $keya => $valuea) {
            if (in_array($valuea, $arrayb)) {
                unset($arraya[$keya]);
            }
        }
        return $arraya;
    }



    public function returnFiltered()
    {
        $allCourses = $this->db->returnCourses(true, "null");
        $allCourses = $this->filterRequirements($allCourses, $this->completedReqs);

        $allCourses = $this->filterByTitle($allCourses, "CDAD");
        $allCourses = $this->filterByTitle($allCourses, "CCEA");
        $allCourses = $this->filterByTitle($allCourses, "CADT");
        $allCourses = $this->filterByTitle($allCourses, "CSTS");

        $allCourses = $this->filterByTitle($allCourses, "CCOL");
        $allCourses = $this->filterByTitle($allCourses, "PHYED");

        if ($this->constraints["9AM"]==TRUE) {
            $coursesWithTime = $this->db->returnCoursesWithTime("09:00");
            $allCourses = $this->compareArrayMulti($allCourses, $coursesWithTime);
        };





        return $allCourses;
    }

    public function returnSelectedCourses(){
      $returnedCourses = [];
      $i=0;
      while ($i<sizeof($this->selectedCourses)){
        $returnedCourses[$i][0] = $this->selectedCourses[$i][0]["title"];
        $returnedCourses[$i][1] = $this->selectedCourses[$i][0]["peopleSoftID"];
        $i++;
      }
      return $returnedCourses;
    }


    public function pushToSession()
    {
        $_SESSION['majorID']=$this->majorID;
        $_SESSION['major']=$this->major;
        $_SESSION['completedReqs']=$this->completedReqs;
        $_SESSION['constraints'] = $this->constraints;
        $_SESSION['selectedCourses'] = $this->selectedCourses;
    }

    public function fetchFromSession()
    {
        //echo "FETCH<br>";
        $this->majorID=$_SESSION['majorID'];
        $this->major=$_SESSION['major'];
        $this->completedReqs=$_SESSION['completedReqs'];
        $this->constraints=$_SESSION['constraints'];
        $this->selectedCourses=$_SESSION['selectedCourses'];
    }
}

$student = new Student();

// $student->setMajor("72");
// $thing = $student->major;
// print_r($thing);
