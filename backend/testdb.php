<?php

include "branchDB.php";

$thing = $db->returnCourses(true, "object");
echo "<pre>";
print_r($thing);
echo "</pre>";




?>
