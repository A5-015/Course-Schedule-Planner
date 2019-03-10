<?php
session_start();

include("ChromePhp.php");

//Including Database configuration file.
require_once("../backend/dbConnector.php");
require_once("../backend/student.php");

if (isset($_POST['search'])) {

   $query = $_POST['search'];
   if(($query != "")&&(strlen($query) > 2)){
     $thing = $student -> db -> returnCourses(FALSE, $query);

      echo "
            <table class='standartLongTable'>
              <tbody>";
    for($x = 0; $x < sizeof($thing); $x++){
        echo "
                  <tr>
                      <td>".$thing[$x][0]."</td>
                      <td>".$thing[$x][1]."</td>
                  </tr>";
      }
      echo "   </tbody>
            </table>";
   }

} else if (isset($_POST['returnAllCourses'])) {

  $thing = $student -> db -> returnCourses(TRUE, "NULL");

  echo "
        <table class='standartTable'>
          <tbody>";
  $counter = 0;
  for ($x = 0; ($x < sizeof($thing)&&($counter < 10)); $x = $x + rand(5, 20)) {
    if (rand(0, 1)){
      echo "
          <tr>
              <td>".$thing[$x][1]."</td>
              <td>".$thing[$x][0]."</td>
          </tr>";

      $counter = $counter + 1;
    }
  }
  echo "    </tbody>
          </table>";

} else if (isset($_POST['peopleSoftID'])) {

  $query = $_POST['peopleSoftID'];

  $student -> fetchFromSession();
  $student -> shareMajorReqs($query);
  $student -> pushToSession();

} else if (isset($_POST['newPeopleSoftID'])) {

  $query = $_POST['newPeopleSoftID'];

  $student -> fetchFromSession();
  //$student -> shareSelectedCourses($query);
  $student -> selectedCourses[] = $student -> db -> returnCourseTime($query);
  $student -> pushToSession();


} else if (isset($_POST['constraintID'])) {

    $query = $_POST['constraintID'];

    $student -> fetchFromSession();

    if($query == "1"){
        if($student -> constraints["9AM"] == false){
          $student -> constraints["9AM"] = true;
        } else {
          $student -> constraints["9AM"] = false;
        }

    }

    if($query == "2"){
      if($student -> constraints["PHYED"] == false){
        $student -> constraints["PHYED"] = true;
      } else {
        $student -> constraints["PHYED"] = false;
      }

    }
    if($query == "3"){
      if($student -> constraints["FYWS"] == false){
        $student -> constraints["FYWS"] = true;
      } else {
        $student -> constraints["FYWS"] = false;
      }

    }
    if($query == "4"){
      if($student -> constraints["CCOL"] == false){
        $student -> constraints["CCOL"] = true;
      } else {
        $student -> constraints["CCOL"] = false;
      }

    }
    if($query == "5"){
      if($student -> constraints["CDAD"] == false){
        $student -> constraints["CDAD"] = true;
      } else {
        $student -> constraints["CDAD"] = false;
      }

    }
    if($query == "6"){
      if($student -> constraints["CCEA"] == false){
        $student -> constraints["CCEA"] = true;
      } else {
        $student -> constraints["CCEA"] = false;
      }

    }
    if($query == "7"){
      if($student -> constraints["CADT"] == false){
        $student -> constraints["CADT"] = true;
      } else {
        $student -> constraints["CADT"] = false;
      }

    }
    if($query == "8"){
      if($student -> constraints["CSTS"] == false){
        $student -> constraints["CSTS"] = true;
      } else {
        $student -> constraints["CSTS"] = false;
      }
    }

    $student -> pushToSession();

} else if (isset($_POST['returnClickableFilteredCourses'])) {
  $_SESSION["requiredContent"] = "requirementSelectionList";
  $_SESSION["requiredContentArgument"] = "filteredAll";
  include "contentCreatorSession.php";
  $_SESSION["requiredContentArgument"] = "";
  $_SESSION["requiredContent"] = "";

} else if (isset($_POST['returnNewClickableFilteredCourses'])) {
  $_SESSION["requiredContent"] = "requirementSelectionList";
  $_SESSION["requiredContentArgument"] = "filteredAllForNewSelection";
  include "contentCreatorSession.php";
  $_SESSION["requiredContentArgument"] = "";
  $_SESSION["requiredContent"] = "";

} else if (isset($_POST['returnClickableSearch'])) {

  $query = $_POST['returnClickableSearch'];
  if(($query != "")&&(strlen($query) > 2)){
    $thing = $student -> db -> returnCourses(FALSE, $query);

     echo "
           <table class='standartLongTable'>
             <tbody>";
   for($x = 0; $x < sizeof($thing); $x++){
      $char = '"';
       echo "
           <tr onclick='processRows(".$char.$thing[$x][1].$char.")>
               <td>".$thing[$x][0]."</td>
               <td>".$thing[$x][1]."</td>
           </tr>";
     }
     echo "   </tbody>
           </table>";
  }

}

?>
