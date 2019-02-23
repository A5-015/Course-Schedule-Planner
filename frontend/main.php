<?php

session_start();

//$step = $_GET["step"];

if (!isset($_SESSION["step"])) {
    $_SESSION["step"] = 0;
}

//check if form was submitted
if(isset($_POST['SubmitButton'])){
  $step = $_SESSION["step"];
  $step = $step + 1;
  $_SESSION["step"] = $step;
}

if(isset($_POST['jumpToStep3'])){
  $_SESSION["step"] = 3;
}

if(isset($_POST['studentMajor'])){
  //echo $_POST['studentMajor'];
}

$step = $_SESSION["step"];

$_SESSION["requiredContent"] = "";

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

    xmlhttp.open("GET","./frontend/contentCreator.php?query="+str,true);
    xmlhttp.send();
  }
</script>
</head>

<body>

  <div class="search-wrap">
    <?php

      if(($step == 0)||($step == 1)){
        echo "
        <object class='logo' type='image/svg+xml' data='./frontend/static/images/logo.svg'>
          Your browser does not support SVG
        </object>
        <br>
        ";
      }

      if($step == 0){

        echo "
        <input type='text' id='search' class='searchTerm'onkeyup='showResult(this.value)' placeholder='Search for classes, professors, subjects...'>
        <i class='fa fa-search' id='searchButton'></i>

        <form action='' method='post' role='form'>
          <button type='submit' class='scheduleMakerButton' name='SubmitButton'>
            <i>Make your class schedule</i>
          </button>
        </form>
        ";

      } else if($step == 1){

        echo "<form action='' method='post' role='form'> ";

        $_SESSION["requiredContent"] = "majorSelectionPageList";
        include "./frontend/contentCreator.php";
        $_SESSION["requiredContent"] = "";

        echo "
          <button type='submit' class='scheduleMakerButton' name='SubmitButton'>
            <i>Continue</i>
          </button>
        </form>

        <form action='' method='post' role='form'>
          <button type='submit' class='scheduleMakerButton2' name='jumpToStep3'>
            <i>I haven't decided my major yet</i>
          </button>
        </form>
        ";

      } else if($step == 2){

        echo $step;

        //table kullan
      }
    ?>

    <hr style="height:40pt; visibility:hidden;" />

    <div id='livesearch'>
    <?php
      if($step == 0){

        $_SESSION["requiredContent"] = "homePageRegularList";
        include "./frontend/contentCreator.php";
        $_SESSION["requiredContent"] = "";

      }
    ?>
    </div>

  </div>
</body>

</html>
