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

class Student
{
    public $major=[];
    public $majorID=[];
    public $completedReqs=[];
    public $constraints=[
      "9AM"   => 0,
      "PHYED"    => 0,
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

    public function shareSelectedCourses($peoplesoftID)
    {

        $selectedPeopleSoft = [];
        $simplifiedSelected = $this->returnSelectedCourses();
        $i=0;
        while ($i<sizeof($simplifiedSelected)) {
            $selectedPeopleSoft[$i] = $simplifiedSelected[$i][1];
            $i++;
        }

        $selectedSearch = array_search($peoplesoftID, $selectedPeopleSoft, true);
        if ($selectedSearch === false) {
            $this->selectedCourses[]= $this->db->returnCourseTime($peoplesoftID);
        }

        else {
          unset($this->selectedCourses[$selectedSearch]);
          $this->selectedCourses = array_values($this->selectedCourses);
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


    //instructions: "newselection" and "completedselection"
    public function returnFiltered($instruction)
    {
        $allCourses = $this->db->returnCourses(true, "null");
        $allCourses = $this->filterRequirements($allCourses, $this->completedReqs);

        $allCourses = $this->filterByTitle($allCourses, "CDAD");
        $allCourses = $this->filterByTitle($allCourses, "CCEA");
        $allCourses = $this->filterByTitle($allCourses, "CADT");
        $allCourses = $this->filterByTitle($allCourses, "CSTS");

        $allCourses = $this->filterByTitle($allCourses, "CCOL");
        $allCourses = $this->filterByTitle($allCourses, "PHYED");

        if ($instruction === "newselection") {
            $trial = $this->returnSelectedCourses();
            for ($x = 0; $x < sizeof($trial); $x++) {
                $allCourses = $this->filterRequirements($allCourses, $trial[$x]);
            }
        }


        if ($this->constraints["9AM"]==true) {
            $coursesWithTime = $this->db->returnCoursesWithTime("09:00");
            $allCourses = $this->compareArrayMulti($allCourses, $coursesWithTime);
        };


        return $allCourses;
    }

    public function returnSelectedCourses()
    {
        $returnedCourses = [];
        $i=0;
        while ($i<sizeof($this->selectedCourses)) {
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

//$student = new Student();

// $student->setMajor("72");
// $thing = $student->major;
// print_r($thing);
