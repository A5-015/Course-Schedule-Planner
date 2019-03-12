<?php

/**************************************************
* Barkin Simsek, bs3528
* Nishant Aswani, nsa325
* March 12, 2019
*
* Object-Oriented Programming, Spring 2019
* Term Project
*
* https://nyuad.app/classes
**************************************************/

// Start a unique session per user to store varaibles on the users browser
session_start();

// Turn off warning that are not related to compilation
//error_reporting(E_ERROR | E_PARSE);

// Include student class
require_once("./backend/student.php");

// Include the class that handles frontend HTML operations
require_once("./frontend/bodyItemCreator.php");
$insert = new BodyItem;

// Create wizard steps if not initiated
if (!isset($_SESSION["step"])) {
    $_SESSION["step"] = 0;
    $_SESSION["refresh"] = 0;
}

// Load content from frontend
require_once("./frontend/main.php");

?>
