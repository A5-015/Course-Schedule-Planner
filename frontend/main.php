<?php

session_start();

//$step = $_GET["step"];

if (!isset($_SESSION["step"])) {
    $_SESSION["step"] = 0;
}

if(isset($_POST['SubmitButton'])){ //check if form was submitted
  $step = $_SESSION["step"];
  $step = $step + 1;
  $_SESSION["step"] = $step;
}

$step = $_SESSION["step"];

//echo $step;


?>

<!DOCTYPE html>
<html>

<head>
  <title>Class Scheduler | nyuad.app</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="./frontend/static/css/main.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://use.fontawesome.com/21797530af.js"></script>
  <script>
  function showResult(str) {
    if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    } else {  // code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function() {
      if (this.readyState==4 && this.status==200) {
        document.getElementById("livesearch").innerHTML=this.responseText;;
      }
    }

    xmlhttp.open("GET","table.php?q="+str,true);
    xmlhttp.send();
  }
</script>
</head>

<body>

  <div class="search-wrap">
    <object class="logo" type="image/svg+xml" data="./frontend/static/images/logo.svg">
      Your browser does not support SVG
    </object>
    <br>
      <input type="text" id="search" class="searchTerm" onkeyup="showResult(this.value)" placeholder="Search for classes, professors, subjects, etc.">

        <i class="fa fa-search" id="searchButton"></i>

    <form action="" method="post" role="form">
      <button type="submit" class="scheduleMakerButton" name="SubmitButton">
        <i>Make your class schedule</i>
      </button>
    </form>

    <hr style="height:40pt; visibility:hidden;" />

  <?php
    if($step == 0){
      echo "
            <div id='livesearch'>
              <table class='bottom-table'>
                <tbody>";

      include "table.php";

      echo "
                </tbody>
              </table>
            </div>";
    }
  ?>

  </div>
</body>

</html>
