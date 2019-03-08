<?php
session_start();

include("ChromePhp.php");

if (!isset($_SESSION["step"])) {
    $_SESSION["step"] = 0;
}

require_once("./backend/student.php");
$step = $_SESSION["step"];
if($step >= 2){
$student -> fetchFromSession();
}

//check if form was submitted
if(isset($_POST['SubmitButton'])){
  $step = $step + 1;
  $_SESSION["step"] = $step;

  if($step == 2){
      $post = array(array($_POST['studentMajorID']));
      $student -> setMajor($post[0][0]);
      $student -> pushToSession();
  }
}

if(isset($_POST['jumpToStep3'])){
  $student -> setMajor(" ");
  $student -> pushToSession();
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

  //Getting value from "ajax.php".
function fill(Value) {
   //Assigning value to "search" div in "search.php" file.
   $('#search').val(Value);
   //Hiding "display" div in "search.php" file.
   $('#display').hide();
}

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}

$(document).ready(function() {
   //On pressing a key on "Search box" in "search.php" file. This function will be called.
   $('#search').keyup(delay(function (e) {
       //Assigning search box value to javascript variable named as "name".
       var name = $('#search').val();
       //Validating, if "name" is empty.
       if (name == "") {
           //Assigning empty value to "display" div in "search.php" file.
           $("#livesearch").html("");
       }
       //If name is not empty.
       else {
           //AJAX is called.
           $.ajax({
               //AJAX type is "Post".
               type: "POST",
               //Data will be sent to "ajax.php".
               url: "./frontend/contentCreatorQuery.php",
               //Data, that will be sent to "ajax.php".
               data: {
                   //Assigning value of "name" into "search" variable.
                   search: name
               },
               //If result found, this funtion will be called.
               success: function(html) {
                   //Assigning result to "display" div in "search.php" file.
                   $("#livesearch").html(html).show();
               }
           });
       }
   }, 300));
});

</script>
</head>

<body>

  <div class="search-wrap">
    <?php

      if(($step == 0)||($step == 1)||($step == 2)||($step == 3)){
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
        include "./frontend/contentCreatorSession.php";
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

        echo "
          <div class='studentInformation'>

          Selected major: ";

          $_SESSION["requiredContent"] = "getMajorNameByID";
          include "./frontend/contentCreatorSession.php";
          $_SESSION["requiredContent"] = "";

        echo " <br> Please select classes that you have already taken
          </div>
        ";

        echo "
          <form action='' method='post' role='form'>
          <button type='submit' class='scheduleMakerButton' name='SubmitButton'>
            <i>Continue</i>
          </button>
          </form>
          ";

        $_SESSION["requiredContent"] = "majorRequirementSelectionList";
        include "./frontend/contentCreatorSession.php";
        $_SESSION["requiredContent"] = "";

      } else if($step == 3){

        echo "
          <div class='studentInformation'>

          Selected major: ";

          $_SESSION["requiredContent"] = "getMajorNameByID";
          include "./frontend/contentCreatorSession.php";
          $_SESSION["requiredContent"] = "";

        echo "
          <br>

          Taken Classes: ";


        $_SESSION["requiredContent"] = "getAlreadyTakenClasses";
        include "./frontend/contentCreatorSession.php";
        $_SESSION["requiredContent"] = "";


        echo "
          </div>
        ";


      }
    ?>

    <hr style="height:40pt; visibility:hidden;" />

    <div id='livesearch'>
    <?php
      if($step == 0){
        $_SESSION["requiredContent"] = "homePageRegularList";
        include "./frontend/contentCreatorSession.php";
        $_SESSION["requiredContent"] = "";
      }
    ?>
    </div>

  </div>
</body>

</html>
