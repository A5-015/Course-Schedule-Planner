<?php
session_start();


$requiredContent = $_SESSION["requiredContent"];
$requiredContentArgument = $_SESSION["requiredContentArgument"];

if ($requiredContent == "homePageRegularList") {
    $thing = $db -> returnCourses(TRUE, "NULL");

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
} else if ($requiredContent == "majorSelectionPageList") {
    $thing = $db->returnAllMajors();

    echo "
          <select name='studentMajorID' class='dropDownMenu'>";
    for ($x = 0; $x < sizeof($thing); $x++) {
        echo "
          <option value='".$thing[$x][1]."' >".$thing[$x][0]."</option>
      ";
    }
    echo "
          </select>";

} else if ($requiredContent == "getMajorNameByID") {
    $thing = $student -> major[0][0];

    if (!isset($thing)) {
        echo "Haven't decided yet";
    } else {
        echo $thing;
    }

} else if ($requiredContent == "requirementSelectionList") {
    if($requiredContentArgument == "major"){
      $thing = $student-> db -> returnMajorReq($student -> majorID);
    } else if($requiredContentArgument == "filteredAll"){
      $thing = $student -> returnFiltered("completedselection");
    } else if($requiredContentArgument == "filteredAllForNewSelection"){
      $thing = $student -> returnFiltered("newselection");
    }

    // Get already completed courses
    $completedReqs = $student -> completedReqs;

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
              if($requiredContentArgument == "filteredAllForNewSelection"){
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


} else if ($requiredContent == "getAlreadyTakenClasses") {
    $student -> fetchFromSession();
    $thing = $student-> db -> returnCourseName($student -> completedReqs);
    $completedReqs = $student -> completedReqs;

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

} else if ($requiredContent == "getSelectedClasses") {

    $student -> fetchFromSession();
    $thing = $student -> returnSelectedCourses();

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
}

?>
