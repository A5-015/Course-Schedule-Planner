<?php
require_once("./backend/dbConnector.php");
require_once("./backend/student.php");

$requiredContent = $_SESSION["requiredContent"];

if ($requiredContent == "homePageRegularList") {
	$thing = $db->returnAllMajors();

  echo "
          <table class='bottom-table'>
            <tbody>";
  for($x = 0; $x < 10; $x++){
      echo "
            <tr>
                <td>.thing[x][0]</td>
                <td>.thing[x][1]</td>
            </tr>";
    }
    echo "    </tbody>
            </table>";

} else if ($requiredContent == "majorSelectionPageList") {
	$thing = $db->returnAllMajors();

  echo "
        <div class='select-style'>
          <select name='studentMajorID'>";
  for($x = 0; $x < sizeof($thing); $x++){
      echo "
          <option value='".$thing[$x][1]."'>".$thing[$x][0]."</option>
      ";
    }
    echo "
          </select>
        </div>";

} else if ($requiredContent == "getMajorNameByID") {
	echo $student -> major[0][0];

} else if ($requiredContent == "majorRequirementSelectionList") {
	$thing = $student-> db -> returnMajorReq($student -> majorID);

	echo "
					<table class='bottom-table-major-requirement'>
						<tbody>";
	for($x = 0; $x < sizeof($thing); $x++){
			echo "
						<tr class='clickable-row'>
								<td>".$thing[$x][0]."</td>
								<td>".$thing[$x][1]."</td>
						</tr>";
		}
		echo "    </tbody>
						</table>";
}

?>
