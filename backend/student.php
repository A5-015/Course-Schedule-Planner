<?php
require_once "dbConnector.php";

session_start();

// $_SESSION['majorID']='$majorID';
// $_SESSION['major']='$major';


class Student
{

  public $major;
  public $majorID;

  public function __construct()
  {
    $this->db = new Database();
  }

  public function setMajor($initMajorID)
  {
    $this->majorID = $initMajorID;
    $this->major = $this->db->returnMajorName($initMajorID);

  }

  public function transfer()
  {
    $_SESSION['majorID']=$majorID;
    $_SESSION['major']=$major;
  }

}

$student = new Student();
// $student->setMajor("72");
// $thing = $student->major;
// print_r($thing);
