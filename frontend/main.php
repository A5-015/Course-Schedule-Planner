<?php

// Get the current wizard step from session
$step = $_SESSION["step"];

if ($step >= 2) {
    // Transfer data stored in the session back to the student class
    $insert -> student -> fetchFromSession();
}

// Check if continue button was submitted
if (isset($_POST['SubmitButton'])) {

    // If button is pressed, increment the wizard step by 1
    $step = $step + 1;
    $_SESSION["step"] = $step;

    // Enable page refreshing for clearing previously submitted form data
    $_SESSION["refresh"] = 1;

    // If the current step is 2, send major information to student class
    if ($step == 2) {
        $insert -> student -> setMajor($_POST['studentMajorID']);

        // Save class data in the session
        $insert -> student -> pushToSession();
    }
}

// If user have not decided his/her major yet, jump to step 3 directly
if (isset($_POST['jumpToStep3'])) {
    $insert -> student -> setMajor(" ");
    $insert -> student -> pushToSession();
    $_SESSION["step"] = 3;

}

// Get the current wizard step from session after the changes are made
$step = $_SESSION["step"];

// Set page title
$pageTitle = "Class Scheduler | nyuad.app | beta";

// Create the head part of the page
// Includes required libraries and links to other pages
require_once("headerCreator.php");

// Create the main content of the page
require_once("bodyCreator.php");
?>
