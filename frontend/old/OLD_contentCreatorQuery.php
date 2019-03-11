<?php
////////////////////////////////////////////////////////////////////////////////
// This page is used for handling the post requests sent by forms and buttons //
////////////////////////////////////////////////////////////////////////////////
session_start();

//Including the student class
require_once("../backend/student.php");

//Including the body item creator
require_once("bodyItemCreator.php");

if (isset($_POST['search'])) {

  insertBodyItem($student, "searchContent", $_POST['search']);

} else if (isset($_POST['returnAllCourses'])) {

  insertBodyItem($student, "returnAllCourses");

} else if (isset($_POST['peopleSoftID'])) {

  insertBodyItem($student, "peopleSoftID", $_POST['peopleSoftID']);

} else if (isset($_POST['newPeopleSoftID'])) {

  insertBodyItem($student, "newPeopleSoftID", $_POST['newPeopleSoftID']);

} else if (isset($_POST['constraintID'])) {

  insertBodyItem($student, "constraintID", $_POST['constraintID']);

} else if (isset($_POST['returnClickableFilteredCourses'])) {

  insertBodyItem($student, "liveSearch", "requirementSelectionList", "filteredAll");

} else if (isset($_POST['returnNewClickableFilteredCourses'])) {

  insertBodyItem($student, "liveSearch", "requirementSelectionList", "filteredAllForNewSelection");

} else if (isset($_POST['returnClickableSearch'])) {

  insertBodyItem($student, "returnClickableSearch", $_POST['returnClickableSearch']);

}

?>
