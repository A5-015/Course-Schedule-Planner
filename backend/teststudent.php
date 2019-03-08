<?php

include "student.php";

$setMajor = $student->setMajor("72");
echo "<pre>";
print_r($student->major);
echo "</pre>";

$setMajorreq = $student->shareMajorReqs("panos");
echo "<pre>";
print_r($student->completedReqs);
echo "</pre>";



?>
