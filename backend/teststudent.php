<?php

include "student.php";

$student = new Student();

echo "<pre>";
$thing = $student-> returnFiltered("completedselection");
echo "</pre>";
?>
