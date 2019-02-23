<?php

include "dbConnector.php";

$thing = $db->returnMajorName("72");
echo "<pre>";
print_r($thing[0][0]);
echo "</pre>";




?>
