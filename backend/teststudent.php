<?php

include "branchStudent.php";

// $setMajor = $student->setMajor("72");ENGR-UH 2013
// echo "<pre>";
// print_r($student->major);
// echo "</pre>";
//
// $setMajorreq = $student->shareMajorReqs("panos");

$student->constraints["CDAD"] = true;
$student->constraints["CSTS"] = true;
$student->constraints["CCOL"] = true;
$student->constraints["PHYED"] = true;



echo "<pre>";
//var_dump($student->constraints["CDAD"] =="1");
echo "<br>";
print_r($student->returnFiltered());

echo "</pre>";



?>
