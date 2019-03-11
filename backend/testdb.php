<?php

include "branchDB.php";

$thing = $db->courseTimeQuery("ENGR-UH 2510");
echo "<pre>";
print_r($thing);
echo "</pre>";



//$keywordQuery = $keywordQuery."AND course.title LIKE '%$keyword%'";
?>
