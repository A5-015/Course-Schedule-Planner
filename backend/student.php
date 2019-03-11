<?php
require_once "dbConnector.php";

class Student
{
    //all of this is information that will be passed to session
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

    //constructor, instantates the db object within it
    //composite design was chosen over inheritance because
    //the Student class does not extend the functionality of the
    //Database class; in fact, Student class also acts as an abstraction
    public function __construct()
    {
        $this->db = new Database();
    }

    //function that sets the major of the student, given the majorID
    public function setMajor($initMajorID)
    {
        $this->majorID = $initMajorID;
        $this->major = $this->db->returnMajorName($initMajorID);
    }

    //function that sets and unsets the completed major requirements
    //given the peopleSoftID
    //when a click is done on the front end
    public function shareMajorReqs($peoplesoftID)
    {
        //looks for peopleSoftID within the array of completedReqs
        $reqSearch = array_search($peoplesoftID, $this->completedReqs, true);
        //if not found
        if ($reqSearch === false) {
            //it simply adds it
            $this->completedReqs[] = $peoplesoftID;
          // if found
        } else {
            //it removes it from the array
            unset($this->completedReqs[$reqSearch]);
            //reindexes the array to avoid null values
            $this->completedReqs = array_values($this->completedReqs);
        }
    }

    //function that sets and unsets the completed courses
    //when a click is done on the front end
    public function shareSelectedCourses($peoplesoftID)
    {
        $selectedPeopleSoft = [];
        //returns selected courses in a 2D array format
        $simplifiedSelected = $this->returnSelectedCourses();

        //iterates over the array and stores only the peopleSoftID
        //for a higher level understanding: the selected courses information
        //is stored as a lot of other "junk" (title, description, times, etc)
        //the goal is to unmap or unravel this and obtain just the peopleSoftID
        //to do something useful
        $i=0;
        while ($i<sizeof($simplifiedSelected)) {
            $selectedPeopleSoft[$i] = $simplifiedSelected[$i][1];
            $i++;
        }

        //with the unmapped, raw peopleSoftID information in an array
        //the given argument is then searched through this simple array
        $selectedSearch = array_search($peoplesoftID, $selectedPeopleSoft, true);

        //if not found, it sends the peopleSoftID to get its time information and store it
        //into the array of selected courses to be displayed on the calendar
        if ($selectedSearch === false) {
            $this->selectedCourses[]= $this->db->courseTimeQuery($peoplesoftID);
        }
        //if found, it simply unsets it from the selectedCourses array
        else {
          unset($this->selectedCourses[$selectedSearch]);
          //reindex to avoid null values
          $this->selectedCourses = array_values($this->selectedCourses);
        }
    }

    //an auxiliary function that looks for the keyword (eg: CDAD)
    //and removes them if the user selected to do so
    public function filterByTitle($allCourses, $title)
    {
        $i=0;
        $offsetArray =[];
        //checks to see whether a given constraint was set to "high"
        if ($this->constraints["$title"] =="1") {
            //iterates through all the courses and gets their offset values
            while ($i < sizeof($allCourses)) {
                if (preg_match("/$title/", $allCourses[$i][1])) {
                    array_push($offsetArray, $i);
                }
                $i++;
            }
            //iterates through the master array and removes the courses
            //based on the offset array
            $i=0;
            while ($i < sizeof($offsetArray)) {
                unset($allCourses[$offsetArray[$i]]);
                $i++;
            }
        }
        //returns a filtered version of the input array
        return $allCourses;
    }

    //auxiliary function that simply removes all the completedReqs
    //from the list of all courses
    public function filterRequirements($allCourses, $completedReqs)
    {
        //iterates through master array comparing against the completedReqs
        $i=0;
        while ($i < sizeof($allCourses)) {
            //if found, it is filtered through the master array
            if (in_array($allCourses[$i][1], $completedReqs)) {
                unset($allCourses[$i]);
            }
            $i++;
        }
        //returns filtered master array
        return $allCourses;
    }

    //an auxiliary function that compares two 2D arrays
    //and filters out what is shared between the two
    //in the first passed array
    //essentially a custom array filter for 2D arrays
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
    //this function is primarily used in displaying under the searchboxes
    //all the courses that have not been completed or have not been set as undesired
    //that are offered the current semester
    public function returnFiltered($instruction)
    {
        //first creates a master list of all courses
        $allCourses = $this->db->returnCourses(true, true,"null");
        //filters them out based on completed requirements
        $allCourses = $this->filterRequirements($allCourses, $this->completedReqs);

        //depending on whether clicked or not, it filters out core requirements,
        //colloquims, PE, and 9 AMs (see below)
        $allCourses = $this->filterByTitle($allCourses, "CDAD");
        $allCourses = $this->filterByTitle($allCourses, "CCEA");
        $allCourses = $this->filterByTitle($allCourses, "CADT");
        $allCourses = $this->filterByTitle($allCourses, "CSTS");
        $allCourses = $this->filterByTitle($allCourses, "CCOL");
        $allCourses = $this->filterByTitle($allCourses, "PHYED");

        //if "newselection" is passed, then it even removes the selected courses
        //that the student wants to take this semester
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

        //returns the filtered courses
        return $allCourses;
    }

    //auxiliary function that aids in unmapping the selectedCourses to
    //extract just the title and peopleSoftID
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


    //shares information with the session, so that when process is over, the information
    //is not destroyed
    public function pushToSession()
    {
        $_SESSION['majorID']=$this->majorID;
        $_SESSION['major']=$this->major;
        $_SESSION['completedReqs']=$this->completedReqs;
        $_SESSION['constraints'] = $this->constraints;
        $_SESSION['selectedCourses'] = $this->selectedCourses;
    }

    //receives the information from session, so it can be operated upon and manipulated
    public function fetchFromSession()
    {
        //echo "FETCH<br>";
        $this->majorID=$_SESSION['majorID'];
        $this->major=$_SESSION['major'];
        $this->completedReqs=$_SESSION['completedReqs'];
        $this->constraints=$_SESSION['constraints'];
        $this->selectedCourses=$_SESSION['selectedCourses'];
    }

    public function __destruct()
    {
    }
}
//$student = new Student();
// $student->setMajor("72");
// $thing = $student->major;
// print_r($thing);
