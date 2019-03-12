<?php

// Start session
session_start();

// Set wizard step to 0
$_SESSION["step"] = 0;

// Destruct the session and other objects
session_destroy();

// Redirect to home page
header("Location: index.php");

?>
