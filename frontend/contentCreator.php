<?php
//include "./backend/dbConnector.php";

$query = $_GET["query"];

$requiredContent = $_SESSION["requiredContent"];


if ($requiredContent == "homePageRegularList") {
	//$thing = $db->returnAllMajors();

  echo "
          <table class='bottom-table'>
            <tbody>";

  //for($x = 0; $x < sizeof($thing); $x++){
    for($x = 0; $x < 10; $x++){
      echo "
            <tr>
                <td>sadasd</td>
                <td>sadas</td>
            </tr>";
    }

    echo "    </tbody>
            </table>";

} else if ($requiredContent == "majorSelectionPageList") {
	//$thing = $db->returnAllMajors();

  echo "
        <div class='select-style'>
          <select name='studentMajor'>";

  //for($x = 0; $x < sizeof($thing); $x++){
    for($x = 0; $x < 10; $x++){
      echo "

          <option value='audi'>Audi TT</option>

      ";
    }

    echo "
          </select>
        </div>";

}

if($query != ""){

    echo "
          <table class='bottom-table'>
            <tbody>";

  //for($x = 0; $x < sizeof($thing); $x++){
    for($x = 0; $x < 10; $x++){
      echo "
            <tr>
                <td>aaaaaaaaaaaaaa</td>
                <td>aaaaaaaaaaaa</td>
            </tr>";
    }

    echo "    </tbody>
            </table>";

}

?>
