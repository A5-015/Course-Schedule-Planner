<?php

include "../backend/dbConnector.php";

$q=$_GET["q"];

if($q !=  ""){

  $thing = $db -> returnAllMajors($q);


  //$thing = $db -> returnAllMajors();

    //print_r($thing);

    for($x = 0; $x < sizeof($thing); $x++){


      echo "
      <table class='bottom-table'>
        <tbody>
      <tr>
            <td>".$thing[$x]."</td>
            <td>name</td>
            <td>professor</td>
          </tr>
          </tbody>
        </table>";
    }

} else {
  echo "
  <table class='bottom-table'>
    <tbody>
  <tr>
            <td>test</td>
            <td>name</td>
            <td>professor</td>
          </tr>
          </tbody>
        </table>";
}

?>
