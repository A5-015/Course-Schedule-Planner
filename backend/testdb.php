<?php

include "branchDB.php";

$thing = $db->returnSelectedCourses();
echo "<pre>";
print_r($thing);
echo "</pre>";




?>
