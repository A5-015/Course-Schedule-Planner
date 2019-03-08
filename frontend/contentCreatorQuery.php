<?php
session_start();

include("ChromePhp.php");

//Including Database configuration file.
require_once("../backend/dbConnector.php");
require_once("../backend/student.php");



if (isset($_POST['search'])) {

   $query = $_POST['search'];
   if(($query != "")&&(strlen($query) > 2)){
     $thing = $db -> returnCourses($query);

      echo "
            <table class='bottom-table'>
              <tbody>";
    for($x = 0; $x < sizeof($thing); $x++){
        echo "
              <tr>
                  <td>".$thing[$x][0]."</td>
                  <td>".$thing[$x][1]."</td>
              </tr>";
      }
      echo "    </tbody>
              </table>";
   }

} else if (isset($_POST['peopleSoftID'])) {

    //ChromePhp::log($_POST['peopleSoftID']);

    $query = $_POST['peopleSoftID'];
    //$query2 = $_POST['courseName'];

    $student -> fetchFromSession();
    $student -> shareMajorReqs($query);
    $student -> pushToSession();

}


          ?>
