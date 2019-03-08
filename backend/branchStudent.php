<?php
require_once "dbConnector.php";
#require_once "ChromePhp.php";

// $_SESSION['majorID']='$majorID';
// $_SESSION['major']='$major';

//completedReqs needs to be in [0][0]


class Student
{
    public $major=[];
    public $majorID=[];
    public $completedReqs=[];

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

        $reqSearch = array_search($peoplesoftID, $this->completedReqs);
        if ($reqSearch == false){
          $this->completedReqs[] = $peoplesoftID;
        }
        else {
          unset($this->completedReqs[$reqSearch]);
        }
    }

    public function pushToSession()
    {
        //echo "PUSH<br>";
        $_SESSION['majorID']=$this->majorID;
        $_SESSION['major']=$this->major;
        $_SESSION['completedReqs']=$this->completedReqs;
    }

    public function fetchFromSession()
    {
        //echo "FETCH<br>";
        $this->majorID=$_SESSION['majorID'];
        $this->major=$_SESSION['major'];
        $this->completedReqs=$_SESSION['completedReqs'];
        // echo $this->major[0][0];
    // echo $this->majorID;
    }
}

$student = new Student();

// $student->setMajor("72");
// $thing = $student->major;
// print_r($thing);
