<?php
session_start();

include("ChromePhp.php");

if (!isset($_SESSION["step"])) {
    $_SESSION["step"] = 0;
}

require_once("./backend/student.php");
$step = $_SESSION["step"];
if ($step >= 2) {
    $student -> fetchFromSession();
}

//check if form was submitted
if (isset($_POST['SubmitButton'])) {
    $step = $step + 1;
    $_SESSION["step"] = $step;

    if ($step == 2) {
        $post = array(array($_POST['studentMajorID']));
        $student -> setMajor($post[0][0]);
        $student -> pushToSession();
    }
}

if (isset($_POST['jumpToStep3'])) {
    $student -> setMajor(" ");
    $student -> pushToSession();
    $_SESSION["step"] = 3;
}

if (isset($_POST['studentMajor'])) {
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

<script>
<?php
for($x = 1; $x < 9; $x++){
  echo "
  function processCheckboxes".$x."() {
    $.ajax( {
        type: 'POST',
        url: './frontend/contentCreatorQuery.php',
        data: {constraintID: ".$x."}
    } );
  }
  ";
}
?>
</script>

</head>

<body>
    <table class="skelethon">
      <tbody>
      <?php
        if (($step == 0)||($step == 1)||($step == 2)||($step == 3)) {
            echo "
            <tr>
                <td>
                  <img src='./frontend/static/images/logo.png' alt='nyuad.app | classes logo' class = 'logo'>
                </td>
            </tr>";
        }

        if ($step == 0) {
            echo "
            <tr>
                <td>
                  <input type='text' id='search' class='searchBox'onkeyup='showResult(this.value)' placeholder='Search for courses, professors, subjects...'>
                  <i class='fa fa-search' id='searchButton'></i>
                </td>
            </tr>

            <tr>
                <td>
                  <form action='' method='post' role='form'>
                    <button type='submit' class='submitButton' name='SubmitButton'>
                      <i>Make your course schedule</i>
                    </button>
                  </form>
                </td>
            </tr>";
        } elseif ($step == 1) {
            echo "
            <tr>
                <td>
                  <form action='' method='post' role='form'> ";

            $_SESSION["requiredContent"] = "majorSelectionPageList";
            include "./frontend/contentCreatorSession.php";
            $_SESSION["requiredContent"] = "";

            echo "
                  <hr style='height:8px; visibility:hidden;' />

                  <button type='submit' class='submitButton' name='SubmitButton'>
                    <i>Continue</i>
                  </button>

                </form>
              </td>
          </tr>

          <tr>
              <td>
                <form action='' method='post' role='form'>
                  <button type='submit' class='submitButton' name='jumpToStep3'>
                    <i>I haven't decided my major yet</i>
                  </button>
                </form>
              </td>
          </tr>";
        } elseif ($step == 2) {
            echo "
            <tr>
                <td>
                    <div class='studentInformationText'>

                      Selected major: ";

            $_SESSION["requiredContent"] = "getMajorNameByID";
            include "./frontend/contentCreatorSession.php";
            $_SESSION["requiredContent"] = "";

            echo "
                      <br> Please select courses that you have already completed
                    </div>
                </td>
            </tr>";

            echo "
            <tr>
                <td>
                    <form action='' method='post' role='form'>
                    <button type='submit' class='submitButton' name='SubmitButton'>
                      <i>Continue</i>
                    </button>
                    </form>
                </td>
            </tr>

            <tr>
                <td>";

            $_SESSION["requiredContent"] = "requirementSelectionList";
            $_SESSION["requiredContentArgument"] = "major";
            include "./frontend/contentCreatorSession.php";
            $_SESSION["requiredContentArgument"] = "";
            $_SESSION["requiredContent"] = "";

            echo "
                </td>
            </tr>";
        } elseif ($step == 3) {
            echo "
            <tr>
                <td>
                  <div class='studentInformationText'>
                    Selected Major: ";

            $_SESSION["requiredContent"] = "getMajorNameByID";
            include "./frontend/contentCreatorSession.php";
            $_SESSION["requiredContent"] = "";

            echo "
                </div>
              </td>
            </tr>

            <tr>
                <td>
                  <div class='studentInformationText'>
                  Completed Courses: ";

            $_SESSION["requiredContent"] = "getAlreadyTakenClasses";
            include "./frontend/contentCreatorSession.php";
            $_SESSION["requiredContent"] = "";

            // Fetching is need to decide for the checked buttons
            $student -> fetchFromSession();
            echo "
                  <br>
                  <br>
                  Please select courses ... i am begging you :(
                  </div>
                </td>
            </tr>

            <tr>
                <td>
                  <input type='text' id='search' class='searchBox'onkeyup='showResult(this.value)' placeholder='Search for courses, professors, subjects...'>
                  <i class='fa fa-search' id='searchButton'></i>
                </td>
            </tr>

            <tr>
                <td>
                    <table class='constarintsTable'>
                    <tr>
                      <td>

                          <input type='checkbox' class='checkbox' id='constraint1' value='9AM' onclick='processCheckboxes1()'
                          "; if($student -> constraints["9AM"]){echo "checked";} echo "
                          >
                          <label for='constraint1' >No 9AM</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint2' value='PE' onclick='processCheckboxes2()'
                            "; if($student -> constraints["PHYED"]){echo "checked";} echo "
                          >
                          <label for='constraint2' >Completed PE</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint3' value='FYWS' onclick='processCheckboxes3()'
                          "; if($student -> constraints["FYWS"]){echo "checked";} echo "
                          >
                          <label for='constraint3'>Completed FYWS</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint4' value='CCOL' onclick='processCheckboxes4()'
                          "; if($student -> constraints["CCOL"]){echo "checked";} echo "
                          >
                          <label for='constraint4' >Completed Colloquium</label>

                      </td>

                      <td>

                          <input type='checkbox' class='checkbox' id='constraint5' value='CDAD' onclick='processCheckboxes5()'
                          "; if($student -> constraints["CDAD"]){echo "checked";} echo "
                          >
                          <label for='constraint5' >Completed CDAD</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint6' value='CCEA' onclick='processCheckboxes6()'
                          "; if($student -> constraints["CCEA"]){echo "checked";} echo "
                          >
                          <label for='constraint6' >Completed CCEA</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint7' value='CADT' onclick='processCheckboxes7()'
                          "; if($student -> constraints["CADT"]){echo "checked";} echo "
                          >
                          <label for='constraint7' >Completed CADT</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint8' value='CSTS' onclick='processCheckboxes8()'
                          "; if($student -> constraints["CSTS"]){echo "checked";} echo "
                          >
                          <label for='constraint8' >Completed CSTS</label>

                      </td>
                    </tr>
                    </table>
                    <hr style='height:8px; visibility:hidden;' />

                    <form action='' method='post' role='form'>
                    <button type='submit' class='submitButton' name='SubmitButton'>
                      <i>Continue</i>
                    </button>
                  </form>
                </td>
            </tr>";
        }
      ?>

          <tr>
              <td>
                <div id='livesearch'>
                <?php
                  if ($step == 0) {
                      $_SESSION["requiredContent"] = "homePageRegularList";
                      include "./frontend/contentCreatorSession.php";
                      $_SESSION["requiredContent"] = "";
                  } elseif ($step == 3) {
                      $_SESSION["requiredContent"] = "requirementSelectionList";
                      $_SESSION["requiredContentArgument"] = "filteredAll";
                      include "./frontend/contentCreatorSession.php";
                      $_SESSION["requiredContentArgument"] = "";
                      $_SESSION["requiredContent"] = "";
                  }
                ?>
            </div>
          </td>
        </tr>

        <tr>
            <td>
              <a href="reset.php">Reset</a>
          </td>
        </tr>

      </tbody>
    </table>
  </body>

</html>
