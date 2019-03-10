<?php
session_start();

// Create wizard steps if not initiated
if (!isset($_SESSION["step"])) {
    $_SESSION["step"] = 0;
    $_SESSION["refresh"] = 0;

}

// Include student class
require_once("./backend/student.php");

// Get the current wizard step from session
$step = $_SESSION["step"];

if ($step >= 2) {
    // Transfer data stored in the session back to the student class
    $student -> fetchFromSession();
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
        $student -> setMajor($_POST['studentMajorID']);

        // Save class data in the session
        $student -> pushToSession();
    }
}

if (isset($_POST['jumpToStep3'])) {
    $student -> setMajor(" ");
    $student -> pushToSession();
    $_SESSION["step"] = 3;

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
  <?php
  if ($_SESSION["refresh"] == 1) {
      $_SESSION["refresh"] = 0;

      echo "
      <script>
          window.location = window.location.href;
      </script>
      ";
  }

   ?>
  <link rel="stylesheet" type="text/css" href="./frontend/static/css/main.css" media="all">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://use.fontawesome.com/21797530af.js"></script>

  <script src="./frontend/static/js/searchBar.js"></script>
  <script src="./frontend/static/js/checkBoxes.js"></script>
  <script src="./frontend/static/js/rows.js"></script>
  <script src="./frontend/static/js/pageScrollFix.js"></script>
</head>

<body>
  <table class="skelethon">
    <tr>
      <td>
    <table class="skelethon">
      <tbody>
      <?php
        if (($step == 0)||($step == 1)||($step == 2)||($step == 3)||($step == 4)) {
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
                  <!--<i class='fa fa-search' id='searchButton'></i>-->
                </td>
            </tr>

            <tr>
                <td>
                  <form action='' method='post' role='form'>
                    <button type='submit' class='submitButton' name='SubmitButton'>
                      <i>Make your course schedule using the wizard</i>
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

                      Selected Major: ";

            $_SESSION["requiredContent"] = "getMajorNameByID";
            include "./frontend/contentCreatorSession.php";
            $_SESSION["requiredContent"] = "";

            echo "
                      <br> Please select courses that you have already completed from your major requirements
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
                  Please select courses that you have already completed
                  </div>
                </td>
            </tr>

            <tr>
                <td>
                  <input type='text' id='search2' class='searchBox' onkeyup='showResult(this.value)' placeholder='Search for courses, professors, subjects...'>
                  <!--<i class='fa fa-search' id='searchButton'></i>-->
                </td>
            </tr>

            <tr>
                <td>
                    <table class='constarintsTable'>
                    <tr>
                      <td>

                          <input type='checkbox' class='checkbox' id='constraint1' value='9AM' onclick='processCheckboxes(1)'
                          ";
            if ($student -> constraints["9AM"]) {
                echo "checked";
            }
            echo "
                          >
                          <label for='constraint1' >No 9AM</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint2' value='PE' onclick='processCheckboxes(2)'
                            ";
            if ($student -> constraints["PHYED"]) {
                echo "checked";
            }
            echo "
                          >
                          <label for='constraint2' >Completed PE</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint3' value='FYWS' onclick='processCheckboxes(3)'
                          ";
            if ($student -> constraints["FYWS"]) {
                echo "checked";
            }
            echo "
                          >
                          <label for='constraint3'>Completed FYWS</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint4' value='CCOL' onclick='processCheckboxes(4)'
                          ";
            if ($student -> constraints["CCOL"]) {
                echo "checked";
            }
            echo "
                          >
                          <label for='constraint4' >Completed Colloquium</label>

                      </td>

                      <td>

                          <input type='checkbox' class='checkbox' id='constraint5' value='CDAD' onclick='processCheckboxes(5)'
                          ";
            if ($student -> constraints["CDAD"]) {
                echo "checked";
            }
            echo "
                          >
                          <label for='constraint5' >Completed CDAD</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint6' value='CCEA' onclick='processCheckboxes(6)'
                          ";
            if ($student -> constraints["CCEA"]) {
                echo "checked";
            }
            echo "
                          >
                          <label for='constraint6' >Completed CCEA</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint7' value='CADT' onclick='processCheckboxes(7)'
                          ";
            if ($student -> constraints["CADT"]) {
                echo "checked";
            }
            echo "
                          >
                          <label for='constraint7' >Completed CADT</label>

                          <br>

                          <input type='checkbox' class='checkbox' id='constraint8' value='CSTS' onclick='processCheckboxes(8)'
                          ";
            if ($student -> constraints["CSTS"]) {
                echo "checked";
            }
            echo "
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
        } elseif ($step == 4) {
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

            echo "
                <br>
                </div>
              </td>
          </tr>

          <tr>
              <td>
                <div class='studentInformationText'>
                Selected Courses: ";

            $_SESSION["requiredContent"] = "getSelectedClasses";
            include "./frontend/contentCreatorSession.php";
            $_SESSION["requiredContent"] = "";

            echo "
                <br>
                <br>
                Please select courses you want to take
                </div>
              </td>
          </tr>

          <tr>
              <td>
                <input type='text' id='search2' class='searchBox'onkeyup='showResult(this.value)' placeholder='Search for courses, professors, subjects...'>
                <!--<i class='fa fa-search' id='searchButton'></i>-->
              </td>
          </tr>

          <tr>
              <td>
                  <form action='' method='post' role='form'>
                  <button type='submit' class='submitButton' name='SubmitButton'>
                    <i>Print Your Schedule</i>
                  </button>
                </form>
              </td>
          </tr>";
        } else if ($step == 5) {
          echo "
            <tr>
              <td class='lastPageText'>
                  <a href='#' onclick='window.print()' >Click to print this page</a> or <a href='reset.php'>create another schedule</a>
              </td>
          </tr>";
          echo "<iframe src='./frontend/calendarCreator.php' class='iframeFinalPage' frameBorder='0' scrolling='no'>";
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
                  } elseif ($step == 4) {
                      $_SESSION["requiredContent"] = "requirementSelectionList";
                      $_SESSION["requiredContentArgument"] = "filteredAllForNewSelection";
                      include "./frontend/contentCreatorSession.php";
                      $_SESSION["requiredContentArgument"] = "";
                      $_SESSION["requiredContent"] = "";
                  }
                ?>
            </div>
          </td>
        </tr>
        <?php
        if($step != 0){
          echo "
           <tr>
            <td>
              <a href='reset.php'>Reset</a>
            </td>
          </tr>
          ";
        }

        ?>
      </tbody>
    </table>
  </td>


    <?php
    if ($step == 4) {
      $char = '"';
        echo "
      <td>
      <table>
      <tr>
          <td>
          <iframe  width='600px' src='./frontend/calendarCreator.php' class='iframe' frameBorder='0' scrolling='no'>
          </td>
      </tr>
      </table>
  </td>
      ";
    }
    ?>

</tr>
</table>
  </body>

</html>
