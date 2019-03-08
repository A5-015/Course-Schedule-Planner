<?php
session_start();

require_once("./backend/dbConnector.php");
require_once("./backend/student.php");

$requiredContent = $_SESSION["requiredContent"];

if ($requiredContent == "homePageRegularList") {
    $thing = $db->returnAllMajors();

    echo "
          <table class='bottom-table'>
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
} elseif ($requiredContent == "majorSelectionPageList") {
    $thing = $db->returnAllMajors();

    echo "
        <div class='select-style'>
          <select name='studentMajorID'>";
    for ($x = 0; $x < sizeof($thing); $x++) {
        echo "
          <option value='".$thing[$x][1]."'>".$thing[$x][0]."</option>
      ";
    }
    echo "
          </select>
        </div>";
} elseif ($requiredContent == "getMajorNameByID") {
    $thing = $student -> major[0][0];

    if (!isset($thing)) {
        echo "Haven't decided yet";
    } else {
        echo $thing;
    }
} elseif ($requiredContent == "majorRequirementSelectionList") {
    $thing = $student-> db -> returnMajorReq($student -> majorID);

    echo "
					<table class='bottom-table-major-requirement'>
						<tbody>";
    for ($x = 0; $x < sizeof($thing); $x++) {
        echo "
						<tr class='clickable-row' id='button".$x."'>
								<td>".$thing[$x][0]."</td>
								<td>".$thing[$x][1]."</td>
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
		
} elseif ($requiredContent == "getAlreadyTakenClasses") {
    $student -> fetchFromSession();
    $thing = $student-> db -> returnCourseName($student -> completedReqs);

    if (isset($thing)) {
        for ($x = 0; $x < sizeof($thing); $x++) {
            echo $thing[$x][0];
            echo ", ";
        }
    } else {
			echo "None";
		}
}
