<?php
/////////////////////////////////////////////////////////////////////////////////
// This page is used for handling the post requests sent by forms and buttons  //
// A seperate page is needed to handle these requests because of the way forms //
// work in the HTML. This code just redirects requests to correct body item    //
/////////////////////////////////////////////////////////////////////////////////
session_start();

// Include student class
require_once("../backend/student.php");

//Including the body item creator
require_once("bodyItemCreator.php");
$insert = new BodyItem;

if (isset($_POST['search'])) {

  $insert -> searchContent($_POST['search']);

} else if (isset($_POST['returnAllCourses'])) {

  $insert -> returnAllCourses($_POST['returnAllCourses']);

} else if (isset($_POST['peopleSoftID'])) {

  $insert -> peopleSoftID($_POST['peopleSoftID']);

} else if (isset($_POST['newPeopleSoftID'])) {

  $insert -> newPeopleSoftID($_POST['newPeopleSoftID']);

} else if (isset($_POST['constraintID'])) {

  $insert -> constraintID($_POST['constraintID']);

} else if (isset($_POST['returnClickableFilteredCourses'])) {

  $insert -> liveSearch("requirementSelectionList", "filteredAll");

} else if (isset($_POST['returnNewClickableFilteredCourses'])) {

  $insert -> liveSearch("requirementSelectionList", "filteredAllForNewSelection");

} else if (isset($_POST['returnClickableSearch'])) {

  $insert -> returnClickableSearch($_POST['returnClickableSearch']);

}

?>
