<?php
///////////////////////////////////////////////////////////////////////////////
// This class handles anything that needs to be expressed in terms of html   //
// Also this is the class that directly interacts with the student class     //
// for any sort of data transaction. This class works more like a translator //
// between human readable content and the coding.                            //
///////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////
// Interface for the BodyItem class //
//////////////////////////////////////
interface BodyItemInterface
{
    // All of the followings, unless noted, inserts content in between <tr><td>
    //      and </td></tr> tags to fit nicely into the table on the page. This
    //      class creates the bare HTML and the main css file in the static
    //      folder describes the final visual formatting of the data

    // Inserts the website logo
    public function logo();

    // Inserts the live search box
    //      Argument take take type of the search box for different search
    //      algorithms to call from student class
    public function searchBox($arg1 = " ");

    // Resets the current session and redirects to the home page
    public function reset();

    // Inserts any sort of plain text into the system
    //      Argument take the text itself to insert
    public function bodyText($arg1 = " ");

    // Inserts direct HTML code if needed for formatting
    // Intended only for small adjustments
    // Does not insert between <tr><td> and </td></tr> tags
    //      Argument take the HTML code to insert
    public function formatting($arg1 = " ");

    // Inserts a clickable button in a submittable form (uses POST requests)
    //      Argument 1 take the name attribute for the button and this name is
    //          used to identify the button when clicked and form is submitted
    //      Argument 2 take the text that will be shown in the button. For example,
    //          this argument should be "Click Me!" if we want a button that says
    //          click me on it.
    public function submitButton($arg1 = " ", $arg2 = " ");

    // Gets the avaliable majors and their IDs from the student class and presents
    //      them in a drop down menu with button that make it submittable
    public function majorSelectionPageList();

    // Inserts the selected major by the user and insers "None" if no major is
    //      chosen
    public function studentInformation();

    // This function inserts courses that are already taken by the user. User
    //      manually puts this data into the system by selecting the course
    //      options shown previously
    public function alreadyTakenClasses();

    // Inserts the new selected courses on the step 4. This is not used to show
    //      completed courses but this is used to dyanmically show newly selected
    //      courses in the page 4
    public function selectedClasses();

    // Creates the scrollable long list of classes that students can take based on
    //      their major selections, given constraints, avaliable classes for the
    //      semester.
    //      Argument 1 takes the name of the respective section of the page
    //            tweak the functionality slightly by not repeating the code
    //            for adding slight improvements
    public function requirementSelectionList($arg1 = " ");

    // Inserts the check boxes used to specify the constraints by the user for the
    //      course timings, and types.
    // Uses student class to store the values submitted by the user
    public function checkBox();

    // Insert the "Click to print this page" button on the last page
    // Does not insert between <tr><td> and </td></tr> tags
    public function lastPageText();

    // Inserts the calendar (calendarCreator) in an iframe
    //      Argument takes the CSS class for the iframe for different formatting
    //            options
    public function calendar($arg1 = " ");

    // Live search inserts a scrollable list of courses filtered based on the
    //      a keyword or constraints.
    // It uses JQuery to enhance its functionality and support realtime search
    //      as the user enters keywords into the search box
    // Arguments take types of the lists neeeded to change the functionality
    //      slightly
    public function liveSearch($arg1 = " ", $arg2 = " ");

    // This function returns results from the database when called during the
    //      live search.
    // JQuery initias the call for this function
    // Argument takes the keyword to search for
    public function searchContent($arg1 = " ");

    // Returns all courses avaliable on the database for the home page list
    public function returnAllCourses();

    // The following two functions are used to pass variables to student class
    //      from user and syncronise the selected and completed classes with
    //      the session to retain data persistancy
    // Arguments take the unique course IDs
    public function peopleSoftID($arg1 = " ");
    public function newPeopleSoftID($arg1 = " ");

    // Handles check boxes when they are clicked and transfer data to student class
    // Argument takes the check box IDs
    public function constraintID($arg1 = " ");
}

//////////////////////////////////////////
// Implementation of the BodyItem class //
//////////////////////////////////////////
class BodyItem implements BodyItemInterface
{

    // Default Constructor
    public function __construct()
    {
        $this->student = new Student();
    }

    // Default Destructor
    public function __destruct()
    {
    }

    private function startRow()
    {
        echo "<tr><td>";
    }

    private function endRow()
    {
        echo "</td></tr>";
    }

    public function logo()
    {
        $this -> startRow();
        echo "<img src='./frontend/static/images/logo.png' alt='nyuad.app | classes logo' class = 'logo'>";
        $this -> endRow();
    }

    public function searchBox($arg1 = " ")
    {
        $this -> startRow();
        echo "<input type='text' id='".$arg1."' class='searchBox'onkeyup='showResult(this.value)' placeholder='Search for courses, professors, subjects...'>
            <!--<i class='fa fa-search' id='searchButton'></i>-->";
        $this -> endRow();
    }

    public function reset()
    {
        $this -> startRow();
        echo "<a href='reset.php'>Reset</a>";
        $this -> endRow();
    }

    public function bodyText($arg1 = " ")
    {
        $this -> startRow();
        echo "<div class='studentInformationText'>";
        echo $arg1;
        echo "</div>";
        $this -> endRow();
    }

    public function formatting($arg1 = " ")
    {
        echo $arg1;
    }

    public function submitButton($arg1 = " ", $arg2 = " ")
    {
        $this -> startRow();
        echo "<form action='' method='post' role='form'>
              <button type='submit' class='submitButton' name='".$arg1."'>
                <i>".$arg2."</i>
              </button>
            </form>";
        $this -> endRow();
    }

    public function majorSelectionPageList()
    {
        $this -> startRow();
        echo "<form action='' method='post' role='form'> ";

        $thing = $this->student->db->returnAllMajors();

        echo "<select name='studentMajorID' class='dropDownMenu'>";
        for ($x = 0; $x < sizeof($thing); $x++) {
            echo "<option value='".$thing[$x][1]."' >".$thing[$x][0]."</option>";
        }
        echo "</select>";

        echo "
                <hr style='height:8px; visibility:hidden;' />

                <button type='submit' class='submitButton' name='SubmitButton'>
                  <i>Continue</i>
                </button>
            </form>";
        $this -> endRow();
    }

    public function studentInformation()
    {
        $this -> startRow();
        echo "
            <div class='studentInformationText'>

              Selected Major: <i>";

        $thing = $this->student -> major[0][0];

        if (!isset($thing)) {
            echo "Haven't decided yet";
        } else {
            echo $thing;
        }
        echo "</i>
            </div>";
        $this -> endRow();
    }

    public function requirementSelectionList($arg1 = " ")
    {
        $this -> startRow();
        if ($arg1 == "major") {
            $thing = $this->student->db->returnMajorReq($this->student -> majorID);
        } elseif ($arg1 == "filteredAll") {
            $thing = $this->student -> returnFiltered("completedselection");
        } elseif ($arg1 == "filteredAllForNewSelection") {
            $thing = $this->student -> returnFiltered("newselection");
        }

        // Get already completed courses
        $completedReqs = $this->student -> completedReqs;

        echo "
          <table class='standartLongTable'>
            <tbody>";
        for ($x = 0; $x < sizeof($thing); $x++) {
            if (in_array($thing[$x][1], $completedReqs)) {
                $cssClass = "clickableTableRowSelected";
            } else {
                $cssClass = "clickableTableRow";
            }
            if ($thing[$x][0]!="") {
                $char = "'";
                if ($arg1 == "filteredAllForNewSelection") {
                    $parameter = $thing[$x][1];
                } else {
                    $parameter = $thing[$x][1];
                }
                echo '
            <tr class="'.$cssClass.'" id="button"'.$x.'" onclick="processRows('.$char.$parameter.$char.')">';

                echo "<td>".$thing[$x][0]."</td>
                <td>".$thing[$x][1]."</td>";
                echo "
            </tr>
            ";
            }
        }
        echo "    </tbody>
            </table>
            ";

        echo "
    <script>
      function processRows(passedPeopleSoftID) {
        $.ajax( {
            type: 'POST',
            url: './frontend/contentCreatorQuery.php',
            data:	{";
        if ($arg1 == "filteredAllForNewSelection") {
            echo "newPeopleSoftID";
        } else {
            echo "peopleSoftID";
        }

        echo ": passedPeopleSoftID}
        } );

        document.location.reload(true);
      }
    </script>
      ";

        $this -> endRow();
    }


    public function alreadyTakenClasses()
    {
        $this -> startRow();
        echo "<div class='studentInformationText'>
    Completed Courses: ";

        $this->student -> fetchFromSession();
        $thing = $this->student->db->returnCourseName($this->student -> completedReqs);
        $completedReqs = $this->student -> completedReqs;

        if (isset($thing)) {
            for ($x = 0; $x < sizeof($thing); $x++) {
                $char = '"';
                echo "<i class='selectedClasses' onclick='processCompletedRows(".$char.$completedReqs[$x].$char.")'>";
                echo $thing[$x][0];
                echo "</i>";

                // Simply don't put comma on the last one
                if ($x != (sizeof($thing)-1)) {
                    echo ",<br>";
                }
            }
        } else {
            echo "<i>None</i>";
        }

        echo "</div>";
        $this -> endRow();
    }


    public function checkBox()
    {
        $this -> startRow();
        // Fetching is need to decide for the checked buttons
        $this->student -> fetchFromSession();

        $constraintsList = array("9AM", "PHYED", "FYWS", "CCOL", "CDAD", "CCEA", "CADT", "CSTS");
        $constraintsListLabel = array("No 9AM", "Completed PE", "Completed FYWS", "Completed CCOL", "Completed CDAD", "Completed CCEA", "Completed CADT", "Completed CSTS");

        echo "
           <table class='constarintsTable'>
           <tr>
             <td>";

        for ($x = 1; $x < sizeof($constraintsList)+1; $x++) {
            echo "<input type='checkbox' class='checkbox' id='constraint".$x."' value='".$constraintsList[$x-1]."' onclick='processCheckboxes(".$x.")'";
            if ($this->student -> constraints[$constraintsList[$x-1]]) {
                echo "checked";
            }
            echo "><label for='constraint".$x."' >".$constraintsListLabel[$x-1]."</label>";

            if ($x == 4) {
                echo "
                </td>
                <td>";
            } else {
                echo "<br>";
            }
        }

        echo "
             </td>
           </tr>
           </table>
           ";
        $this -> endRow();
    }

    public function selectedClasses()
    {
        $this -> startRow();
        echo "<div class='studentInformationText'>
        Selected Courses: ";

        $this->student -> fetchFromSession();
        $thing = $this->student -> returnSelectedCourses();

        if (isset($thing)) {
            for ($x = 0; $x < sizeof($thing); $x++) {
                $char = '"';
                echo "<i class='selectedClasses' onclick='processNewRows(".$char.$thing[$x][1].$char.")'>";
                echo $thing[$x][0];
                echo "</i>";

                // Simply don't put comma on the last one
                if ($x != (sizeof($thing)-1)) {
                    echo ",<br>";
                }
            }
        } else {
            echo "None";
        }

        echo "</div>";
        $this -> endRow();
    }


    public function lastPageText()
    {
        echo "<a href='#' onclick='window.print()' >Click to print this page</a> or <a href='reset.php'>create another schedule</a>";
    }

    public function calendar($arg1 = " ")
    {
        $this -> startRow();
        echo "<iframe src='./frontend/calendarCreator.php' class='".$arg1."' frameBorder='0' scrolling='no'>";
        $this -> endRow();
    }


    public function liveSearch($arg1 = " ", $arg2 = " ")
    {
        $this -> startRow();
        echo "<div id='livesearch'>";
        if ($arg1 == "homePageRegularList") {
            $thing = $this->student->db->returnCourses(true, true, "NULL");

            echo "
              <table class='standartTable'>
                <tbody>";
            $counter = 0;
            for ($x = 0; ($x < sizeof($thing)&&($counter < 10)); $x = $x + rand(5, 20)) {
                if (rand(0, 1)) {
                    echo "
                <tr>
                    <td>".$thing[$x][1]."</td>
                    <td>".$thing[$x][0]."</td>
                </tr>";

                    $counter = $counter + 1;
                }
            }
            echo "    </tbody>
                </table>";
        } elseif ($arg1 == "requirementSelectionList") {
            if ($arg2 == "filteredAll") {
                $thing = $this->student -> returnFiltered("completedselection");
            } elseif ($arg2 == "filteredAllForNewSelection") {
                $thing = $this->student -> returnFiltered("newselection");
            }

            // Get already completed courses
            $completedReqs = $this->student -> completedReqs;

            echo "
              <table class='standartLongTable'>
                <tbody>";
            for ($x = 0; $x < sizeof($thing); $x++) {
                if (in_array($thing[$x][1], $completedReqs)) {
                    $cssClass = "clickableTableRowSelected";
                } else {
                    $cssClass = "clickableTableRow";
                }
                if ($thing[$x][0]!="") {
                    $char = "'";
                    if ($arg2 == "filteredAllForNewSelection") {
                        $parameter = $thing[$x][1];
                    } else {
                        $parameter = $thing[$x][1];
                    }
                    echo '
                <tr class="'.$cssClass.'" id="button"'.$x.'" onclick="processRows('.$char.$parameter.$char.')">';

                    echo "<td>".$thing[$x][0]."</td>
                    <td>".$thing[$x][1]."</td>";
                    echo "
                </tr>
                ";
                }
            }
            echo "    </tbody>
                </table>
                ";

            echo "
        <script>
          function processRows(passedPeopleSoftID) {
            $.ajax( {
                type: 'POST',
                url: './frontend/contentCreatorQuery.php',
                data:	{";
            if ($arg2 == "filteredAllForNewSelection") {
                echo "newPeopleSoftID";
            } else {
                echo "peopleSoftID";
            }

            echo ": passedPeopleSoftID}
            } );

            document.location.reload(true);
          }
        </script>
          ";
        }

        echo "</div>";
        $this -> endRow();
    }


    public function searchContent($arg1 = " ")
    {
        $this -> startRow();
        if (($arg1 != "")&&(strlen($arg1) > 2)) {
            $thing = $this->student -> db -> returnCourses(false, true, $arg1);

            echo "
               <table class='standartLongTable'>
                 <tbody>";
            for ($x = 0; $x < sizeof($thing); $x++) {
                echo "
                     <tr>
                         <td>".$thing[$x][0]."</td>
                         <td>".$thing[$x][1]."</td>
                     </tr>";
            }
            echo "   </tbody>
               </table>";
        }
        $this -> endRow();
    }


    public function returnAllCourses()
    {
        $this -> startRow();
        $thing = $this->student -> db -> returnCourses(true, true, "NULL");

        echo "
        <table class='standartTable'>
          <tbody>";
        $counter = 0;
        for ($x = 0; ($x < sizeof($thing)&&($counter < 10)); $x = $x + rand(5, 20)) {
            if (rand(0, 1)) {
                echo "
          <tr>
              <td>".$thing[$x][1]."</td>
              <td>".$thing[$x][0]."</td>
          </tr>";

                $counter = $counter + 1;
            }
        }
        echo "    </tbody>
          </table>";
        $this -> endRow();
    }


    public function peopleSoftID($arg1 = " ")
    {
        $this->student -> fetchFromSession();
        $this->student -> shareMajorReqs($arg1);
        $this->student -> pushToSession();
    }

    public function newPeopleSoftID($arg1 = " ")
    {
        $this->student -> fetchFromSession();
        $this->student -> shareSelectedCourses($arg1);
        $this->student -> pushToSession();
    }


    public function constraintID($arg1 = " ")
    {
        $this->student -> fetchFromSession();

        $constraintsList = ["9AM", "PHYED", "FYWS", "CCOL", "CDAD", "CCEA", "CADT", "CSTS"];

        if ($this->student -> constraints[$constraintsList[$arg1-1]] == false) {
            $this->student -> constraints[$constraintsList[$arg1-1]] = true;
        } else {
            $this->student -> constraints[$constraintsList[$arg1-1]] = false;
        }

        $this->student -> pushToSession();
    }


    public function returnClickableSearch($arg1 = " ")
    {
        $this -> startRow();
        if (($arg1 != "")&&(strlen($arg1) > 2)) {
            $thing = $this->student -> db -> returnCourses(false, false, $arg1);

            echo "
           <table class='standartLongTable'>
             <tbody>";
            for ($x = 0; $x < sizeof($thing); $x++) {
                $char = '"';
                //<tr onclick='processRows(".$char.$thing[$x][1].$char.")>
                echo "
           <tr>
               <td>".$thing[$x][0]."</td>
               <td>".$thing[$x][1]."</td>
           </tr>";
            }
            echo "   </tbody>
           </table>";
        }
        $this -> endRow();
    }
}
