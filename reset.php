<?php

session_start();


$_SESSION["step"] = 0;


session_destroy();

header("Location: index.php");
?>
