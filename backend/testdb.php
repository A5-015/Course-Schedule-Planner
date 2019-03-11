<?php

include "branchDB.php";

$thing = $db->returnMajorReq("72");
echo "<pre>";
print_r($thing);
echo "</pre>";



//$keywordQuery = $keywordQuery."AND course.title LIKE '%$keyword%'";
?>
