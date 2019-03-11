<?php
////////////////////////////////////////////////////////////////////////////////
//   This page handles anything that needs to be expressed in terms of html   //
////////////////////////////////////////////////////////////////////////////////
function insertBodyItem($studentClass, $givenItem, $givenArgument1 = " ", $givenArgument2 = " ") {

  echo "<tr> <td>";

  //////////////////////////////////////////////////////////////////////////////
  if($givenItem == "logo"){
    echo "<img src='./frontend/static/images/logo.png' alt='nyuad.app | classes logo' class = 'logo'>";

  //////////////////////////////////////////////////////////////////////////////
  } else if($givenItem == "searchBox"){
    echo "<input type='text' id='".$givenArgument1."' class='searchBox'onkeyup='showResult(this.value)' placeholder='Search for courses, professors, subjects...'>
          <!--<i class='fa fa-search' id='searchButton'></i>-->";

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "reset"){
    echo "<a href='reset.php'>Reset</a>";

  //////////////////////////////////////////////////////////////////////////////
  } else if($givenItem == "bodyText"){
    echo "<div class='studentInformationText'>";
    echo $givenArgument1;
    echo "</div>";

  //////////////////////////////////////////////////////////////////////////////
  } else if($givenItem == "formatting"){
    echo $givenArgument1;

  //////////////////////////////////////////////////////////////////////////////
  } else if($givenItem == "submitButton"){
    echo "<form action='' method='post' role='form'>
            <button type='submit' class='submitButton' name='".$givenArgument1."'>
              <i>".$givenArgument2."</i>
            </button>
          </form>";

  //////////////////////////////////////////////////////////////////////////////
  } else if($givenItem == "majorSelectionPageList"){
    echo "<form action='' method='post' role='form'> ";

          $thing = $studentClass->db->returnAllMajors();

          echo "<select name='studentMajorID' class='dropDownMenu'>";
                for ($x = 0; $x < sizeof($thing); $x++) {
                    echo "<option value='".$thing[$x][1]."' >".$thing[$x][0]."</option>";
                }
          echo "</select>";

    echo "
              <hr style='height:8px; visibility:hidden;' />

              <button type='submit' class='submitButton' name='SubmitButton'>
                <i>Continue</i>
              </button>
          </form>";

  //////////////////////////////////////////////////////////////////////////////
  } else if($givenItem == "studentInformation"){
    echo "<form action='' method='post' role='form'> ";

    echo "
    <tr>
        <td>
            <div class='studentInformationText'>

              Selected Major: ";

              $thing = $studentClass -> major[0][0];

              if (!isset($thing)) {
                  echo "Haven't decided yet";
              } else {
                  echo $thing;
              }

    echo "
            </div>
        </td>
    </tr>";

  //////////////////////////////////////////////////////////////////////////////
  } else if($givenItem == "requirementSelectionList"){
    if($givenArgument1 == "major"){
      $thing = $studentClass-> db -> returnMajorReq($studentClass -> majorID);
    } else if($givenArgument1 == "filteredAll"){
      $thing = $studentClass -> returnFiltered("completedselection");
    } else if($givenArgument1 == "filteredAllForNewSelection"){
      $thing = $studentClass -> returnFiltered("newselection");
    }

    // Get already completed courses
    $completedReqs = $studentClass -> completedReqs;

    echo "
					<table class='standartLongTable'>
						<tbody>";
    for ($x = 0; $x < sizeof($thing); $x++) {
      if(in_array($thing[$x][1], $completedReqs)){
        $cssClass = "clickableTableRowSelected";
      } else {
        $cssClass = "clickableTableRow";
      }
    if($thing[$x][0]!=""){
      $char = "'";
      if($requiredContentArgument == "filteredAllForNewSelection"){
        $parameter = $thing[$x][1];
      } else {
        $parameter = $thing[$x][1];
      }
        echo '
						<tr class="'.$cssClass.'" id="button"'.$x.'" onclick="processRows('.$char.$parameter.$char.')">';

								echo "<td>".$thing[$x][0]."</td>
                <td>".$thing[$x][1]."</td>";
            echo "
						</tr>
						";
      }
    }
    echo "    </tbody>
						</table>
						";

    echo "
    <script>
      function processRows(passedPeopleSoftID) {
        $.ajax( {
            type: 'POST',
            url: './frontend/contentCreatorQuery.php',
            data:	{";
              if($givenArgument1 == "filteredAllForNewSelection"){
                echo "newPeopleSoftID";
              } else {
                echo "peopleSoftID";
              }

              echo ": passedPeopleSoftID}
        } );

        document.location.reload(true);
      }
    </script>
      ";

  //////////////////////////////////////////////////////////////////////////////
  } else if($givenItem == "alreadyTakenClasses"){

    echo "<div class='studentInformationText'>
    Completed Courses: ";

    $studentClass -> fetchFromSession();
    $thing = $studentClass-> db -> returnCourseName($studentClass -> completedReqs);
    $completedReqs = $studentClass -> completedReqs;

    if (isset($thing)) {
        for ($x = 0; $x < sizeof($thing); $x++) {
            $char = '"';
            echo "<i class='selectedClasses' onclick='processCompletedRows(".$char.$completedReqs[$x].$char.")'>";
            echo $thing[$x][0];
            echo "</i>";

            // Simply don't put comma on the last one
            if($x != (sizeof($thing)-1)){
              echo ",<br>";
            }
        }
    } else {
      echo "None";
    }

    echo "</div>";

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "checBox"){

  // Fetching is need to decide for the checked buttons
  $studentClass -> fetchFromSession();

  echo "
          <table class='constarintsTable'>
          <tr>
            <td>

                <input type='checkbox' class='checkbox' id='constraint1' value='9AM' onclick='processCheckboxes(1)'
                ";
  if ($studentClass -> constraints["9AM"]) {
      echo "checked";
  }
  echo "
                >
                <label for='constraint1' >No 9AM</label>

                <br>

                <input type='checkbox' class='checkbox' id='constraint2' value='PE' onclick='processCheckboxes(2)'
                  ";
  if ($studentClass -> constraints["PHYED"]) {
      echo "checked";
  }
  echo "
                >
                <label for='constraint2' >Completed PE</label>

                <br>

                <input type='checkbox' class='checkbox' id='constraint3' value='FYWS' onclick='processCheckboxes(3)'
                ";
  if ($studentClass -> constraints["FYWS"]) {
      echo "checked";
  }
  echo "
                >
                <label for='constraint3'>Completed FYWS</label>

                <br>

                <input type='checkbox' class='checkbox' id='constraint4' value='CCOL' onclick='processCheckboxes(4)'
                ";
  if ($studentClass -> constraints["CCOL"]) {
      echo "checked";
  }
  echo "
                >
                <label for='constraint4' >Completed Colloquium</label>

            </td>

            <td>

                <input type='checkbox' class='checkbox' id='constraint5' value='CDAD' onclick='processCheckboxes(5)'
                ";
  if ($studentClass -> constraints["CDAD"]) {
      echo "checked";
  }
  echo "
                >
                <label for='constraint5' >Completed CDAD</label>

                <br>

                <input type='checkbox' class='checkbox' id='constraint6' value='CCEA' onclick='processCheckboxes(6)'
                ";
  if ($studentClass -> constraints["CCEA"]) {
      echo "checked";
  }
  echo "
                >
                <label for='constraint6' >Completed CCEA</label>

                <br>

                <input type='checkbox' class='checkbox' id='constraint7' value='CADT' onclick='processCheckboxes(7)'
                ";
  if ($studentClass -> constraints["CADT"]) {
      echo "checked";
  }
  echo "
                >
                <label for='constraint7' >Completed CADT</label>

                <br>

                <input type='checkbox' class='checkbox' id='constraint8' value='CSTS' onclick='processCheckboxes(8)'
                ";
  if ($studentClass -> constraints["CSTS"]) {
      echo "checked";
  }
  echo "
                >
                <label for='constraint8' >Completed CSTS</label>

            </td>
          </tr>
          </table>
          ";
  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "selectedClasses"){

  echo "<div class='studentInformationText'>
  Selected Courses: ";

  $studentClass -> fetchFromSession();
  $thing = $studentClass -> returnSelectedCourses();

  if (isset($thing)) {
      for ($x = 0; $x < sizeof($thing); $x++) {
          $char = '"';
          echo "<i class='selectedClasses' onclick='processNewRows(".$char.$thing[$x][1].$char.")'>";
          echo $thing[$x][0];
          echo "</i>";

          // Simply don't put comma on the last one
          if($x != (sizeof($thing)-1)){
            echo ",<br>";
          }
      }
  } else {
    echo "None";
  }

  echo "</div>";

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "lastPageText"){

  echo "<a href='#' onclick='window.print()' >Click to print this page</a> or <a href='reset.php'>create another schedule</a>";

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "calendar"){

  echo "<iframe src='./frontend/calendarCreator.php' class='".$givenArgument1."' frameBorder='0' scrolling='no'>";

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "liveSearch"){
    echo "<div id='livesearch'>";
      if($givenArgument1 == "homePageRegularList"){

          $thing = $studentClass -> db -> returnCourses(TRUE, "NULL");

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

      } else if($givenArgument1 == "requirementSelectionList"){

          if($givenArgument2 == "filteredAll"){
            $thing = $studentClass -> returnFiltered("completedselection");
          } else if($givenArgument2 == "filteredAllForNewSelection"){
            $thing = $studentClass -> returnFiltered("newselection");
          }

          // Get already completed courses
          $completedReqs = $studentClass -> completedReqs;

          echo "
                <table class='standartLongTable'>
                  <tbody>";
          for ($x = 0; $x < sizeof($thing); $x++) {
            if(in_array($thing[$x][1], $completedReqs)){
              $cssClass = "clickableTableRowSelected";
            } else {
              $cssClass = "clickableTableRow";
            }
          if($thing[$x][0]!=""){
            $char = "'";
            if($givenArgument2 == "filteredAllForNewSelection"){
              $parameter = $thing[$x][1];
            } else {
              $parameter = $thing[$x][1];
            }
              echo '
                  <tr class="'.$cssClass.'" id="button"'.$x.'" onclick="processRows('.$char.$parameter.$char.')">';

                      echo "<td>".$thing[$x][0]."</td>
                      <td>".$thing[$x][1]."</td>";
                  echo "
                  </tr>
                  ";
            }
          }
          echo "    </tbody>
                  </table>
                  ";

          echo "
          <script>
            function processRows(passedPeopleSoftID) {
              $.ajax( {
                  type: 'POST',
                  url: './frontend/contentCreatorQuery.php',
                  data:	{";
                    if($givenArgument2 == "filteredAllForNewSelection"){
                      echo "newPeopleSoftID";
                    } else {
                      echo "peopleSoftID";
                    }

                    echo ": passedPeopleSoftID}
              } );

              document.location.reload(true);
            }
          </script>
            ";

      }

  echo "</div>";

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "searchContent"){

    if(($givenArgument1 != "")&&(strlen($givenArgument1) > 2)){

      $thing = $studentClass -> db -> returnCourses(FALSE, $givenArgument1);

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

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "returnAllCourses"){

  $thing = $studentClass -> db -> returnCourses(TRUE, "NULL");

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

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "peopleSoftID"){

  $studentClass -> fetchFromSession();
  $studentClass -> shareMajorReqs($givenArgument1);
  $studentClass -> pushToSession();

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "newPeopleSoftID"){

  $studentClass -> fetchFromSession();
  //$studentClass -> shareSelectedCourses($givenArgument1);
  $studentClass -> selectedCourses[] = $studentClass -> db -> returnCourseTime($givenArgument1);
  $studentClass -> pushToSession();

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "constraintID"){

  $studentClass -> fetchFromSession();

  if($givenArgument1 == "1"){
      if($studentClass -> constraints["9AM"] == false){
        $studentClass -> constraints["9AM"] = true;
      } else {
        $studentClass -> constraints["9AM"] = false;
      }

  }

  if($givenArgument1 == "2"){
    if($studentClass -> constraints["PHYED"] == false){
      $studentClass -> constraints["PHYED"] = true;
    } else {
      $studentClass -> constraints["PHYED"] = false;
    }

  }
  if($givenArgument1 == "3"){
    if($studentClass -> constraints["FYWS"] == false){
      $studentClass -> constraints["FYWS"] = true;
    } else {
      $studentClass -> constraints["FYWS"] = false;
    }

  }
  if($givenArgument1 == "4"){
    if($studentClass -> constraints["CCOL"] == false){
      $studentClass -> constraints["CCOL"] = true;
    } else {
      $studentClass -> constraints["CCOL"] = false;
    }

  }
  if($givenArgument1 == "5"){
    if($studentClass -> constraints["CDAD"] == false){
      $studentClass -> constraints["CDAD"] = true;
    } else {
      $studentClass -> constraints["CDAD"] = false;
    }

  }
  if($givenArgument1 == "6"){
    if($studentClass -> constraints["CCEA"] == false){
      $studentClass -> constraints["CCEA"] = true;
    } else {
      $studentClass -> constraints["CCEA"] = false;
    }

  }
  if($givenArgument1 == "7"){
    if($studentClass -> constraints["CADT"] == false){
      $studentClass -> constraints["CADT"] = true;
    } else {
      $studentClass -> constraints["CADT"] = false;
    }

  }
  if($givenArgument1 == "8"){
    if($studentClass -> constraints["CSTS"] == false){
      $studentClass -> constraints["CSTS"] = true;
    } else {
      $studentClass -> constraints["CSTS"] = false;
    }
  }

  $studentClass -> pushToSession();

  //////////////////////////////////////////////////////////////////////////////
} else if($givenItem == "returnClickableSearch"){

  if(($givenArgument1 != "")&&(strlen($givenArgument1) > 2)){
    $thing = $studentClass -> db -> returnCourses(FALSE, $givenArgument1);

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


  echo "</td> </tr>";

}

?>
