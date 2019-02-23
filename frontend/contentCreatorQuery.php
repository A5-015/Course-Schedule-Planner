<?php

//Including Database configuration file.
include "../backend/dbConnector.php";

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

}
?>
