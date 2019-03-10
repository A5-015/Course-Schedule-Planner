<?php

include "branchStudent.php";

$student->selectedCourses[] = $student->db->returnCourseTime("ACS-UH 1010X");
$student->selectedCourses[] = $student->db->returnCourseTime("THEAT-UH 1512");
$student->selectedCourses[] = $student->db->returnCourseTime("ACS-UH 2613X");
$student->selectedCourses[] = $student->db->returnCourseTime("ARABL-UH 1110");

echo "<pre>";
print_r($student->returnSelectedCourses());
echo "</pre>";
?>
