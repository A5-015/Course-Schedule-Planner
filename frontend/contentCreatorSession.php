<?php
session_start();

require_once("./backend/dbConnector.php");
require_once("./backend/student.php");

$requiredContent = $_SESSION["requiredContent"];
$requiredContentArgument = $_SESSION["requiredContentArgument"];

if ($requiredContent == "homePageRegularList") {
    $thing = $db->returnAllMajors();

    echo "
          <table class='standartTable'>
            <tbody>";
    for ($x = 0; $x < 10; $x++) {
        echo "
            <tr>
                <td>Professors Name - Lastname</td>
                <td>Class Name</td>
            </tr>";
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
      $thing = $student -> returnFiltered();
    }


    echo "
					<table class='standartLongTable'>
						<tbody>";
    for ($x = 0; $x < sizeof($thing); $x++) {
        echo "
						<tr class='clickableTableRow' id='button".$x."'>";
              if($thing[$x][0]!=""){
								echo "<td>".$thing[$x][0]."</td>
                <td>".$thing[$x][1]."</td>";
              }
            echo "
						</tr>
						";
    }
    echo "    </tbody>
						</table>
						<script>
						";
    /*

    if($(this).css('background') == '#57068C') {
                $(this).css('color', 'white');
                $(this).css('background', 'black');
    }
    else {
             $(this).css('color', 'white');
             $(this).css('background', '#57068C');
    }

    */
    for ($x = 0; $x < sizeof($thing); $x++) {
        echo "
							$('#button".$x."').click(function() {
								$.ajax({
										url:'./frontend/contentCreatorQuery.php',
										type: 'POST',
										data:	{peopleSoftID:'".$thing[$x][1]."', courseName:'".$thing[$x][0]."'}
								 });

							});
							";
    }
    echo "</script>";

} else if ($requiredContent == "getAlreadyTakenClasses") {
    $student -> fetchFromSession();
    $thing = $student-> db -> returnCourseName($student -> completedReqs);

    if (isset($thing)) {
        for ($x = 0; $x < sizeof($thing); $x++) {
            echo $thing[$x][0];

						// Simply don't put comma on the last one
						if($x != (sizeof($thing)-1)){
							echo ", ";
						}
        }
    } else {
			echo "None";
		}

}

?>
