<?php

echo "
      <!DOCTYPE html>
      <html>
      <head>
        <title>".$pageTitle."</title>

        <meta charset='UTF-8'>";

        // Refresh page once to prevent accidental form resubmission
        if ($_SESSION["refresh"] == 1) {
            $_SESSION["refresh"] = 0;
            echo "<script> window.location = window.location.href; </script>";
        }

echo "
        <link rel='stylesheet' type='text/css' href='./frontend/static/css/main.css' media='all'>

        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script src='https://use.fontawesome.com/21797530af.js'></script>

        <script src='./frontend/static/js/searchBar.js'></script>
        <script src='./frontend/static/js/checkBoxes.js'></script>
        <script src='./frontend/static/js/rows.js'></script>
        <script src='./frontend/static/js/pageScrollFix.js'></script>
      </head>
    ";

?>
