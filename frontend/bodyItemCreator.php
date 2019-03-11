<?php
////////////////////////////////////////////////////////////////////////////////
//   This page handles anything that needs to be expressed in terms of html   //
////////////////////////////////////////////////////////////////////////////////

class BodyItem
{

  // Constructor
    public function __construct()
    {
        $this->student = new Student();
    }

    // Destructor
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

              Selected Major: ";

        $thing = $this->student -> major[0][0];

        if (!isset($thing)) {
            echo "Haven't decided yet";
        } else {
            echo $thing;
        }
        echo "
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
            echo "None";
        }

        echo "</div>";
        $this -> endRow();
    }


    public function checBox()
    {
        $this -> startRow();
        // Fetching is need to decide for the checked buttons
        $this->student -> fetchFromSession();

        echo "
           <table class='constarintsTable'>
           <tr>
             <td>

                 <input type='checkbox' class='checkbox' id='constraint1' value='9AM' onclick='processCheckboxes(1)'
                 ";
        if ($this->student -> constraints["9AM"]) {
            echo "checked";
        }
        echo "
                 >
                 <label for='constraint1' >No 9AM</label>

                 <br>

                 <input type='checkbox' class='checkbox' id='constraint2' value='PE' onclick='processCheckboxes(2)'
                   ";
        if ($this->student -> constraints["PHYED"]) {
            echo "checked";
        }
        echo "
                 >
                 <label for='constraint2' >Completed PE</label>

                 <br>

                 <input type='checkbox' class='checkbox' id='constraint3' value='FYWS' onclick='processCheckboxes(3)'
                 ";
        if ($this->student -> constraints["FYWS"]) {
            echo "checked";
        }
        echo "
                 >
                 <label for='constraint3'>Completed FYWS</label>

                 <br>

                 <input type='checkbox' class='checkbox' id='constraint4' value='CCOL' onclick='processCheckboxes(4)'
                 ";
        if ($this->student -> constraints["CCOL"]) {
            echo "checked";
        }
        echo "
                 >
                 <label for='constraint4' >Completed Colloquium</label>

             </td>

             <td>

                 <input type='checkbox' class='checkbox' id='constraint5' value='CDAD' onclick='processCheckboxes(5)'
                 ";
        if ($this->student -> constraints["CDAD"]) {
            echo "checked";
        }
        echo "
                 >
                 <label for='constraint5' >Completed CDAD</label>

                 <br>

                 <input type='checkbox' class='checkbox' id='constraint6' value='CCEA' onclick='processCheckboxes(6)'
                 ";
        if ($this->student -> constraints["CCEA"]) {
            echo "checked";
        }
        echo "
                 >
                 <label for='constraint6' >Completed CCEA</label>

                 <br>

                 <input type='checkbox' class='checkbox' id='constraint7' value='CADT' onclick='processCheckboxes(7)'
                 ";
        if ($this->student -> constraints["CADT"]) {
            echo "checked";
        }
        echo "
                 >
                 <label for='constraint7' >Completed CADT</label>

                 <br>

                 <input type='checkbox' class='checkbox' id='constraint8' value='CSTS' onclick='processCheckboxes(8)'
                 ";
        if ($this->student -> constraints["CSTS"]) {
            echo "checked";
        }
        echo "
                 >
                 <label for='constraint8' >Completed CSTS</label>

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
            $thing = $this->student->db->returnCourses(true, "NULL");

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
            $thing = $this->student -> db -> returnCourses(false, $arg1);

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
        $thing = $this->student -> db -> returnCourses(true, "NULL");

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
        //$this->student -> shareSelectedCourses($arg1);
        $this->student -> selectedCourses[] = $this->student -> db -> returnCourseTime($arg1);
        $this->student -> pushToSession();
    }


    public function constraintID($arg1 = " ")
    {
        $this->student -> fetchFromSession();

        if ($arg1 == "1") {
            if ($this->student -> constraints["9AM"] == false) {
                $this->student -> constraints["9AM"] = true;
            } else {
                $this->student -> constraints["9AM"] = false;
            }
        }

        if ($arg1 == "2") {
            if ($this->student -> constraints["PHYED"] == false) {
                $this->student -> constraints["PHYED"] = true;
            } else {
                $this->student -> constraints["PHYED"] = false;
            }
        }
        if ($arg1 == "3") {
            if ($this->student -> constraints["FYWS"] == false) {
                $this->student -> constraints["FYWS"] = true;
            } else {
                $this->student -> constraints["FYWS"] = false;
            }
        }
        if ($arg1 == "4") {
            if ($this->student -> constraints["CCOL"] == false) {
                $this->student -> constraints["CCOL"] = true;
            } else {
                $this->student -> constraints["CCOL"] = false;
            }
        }
        if ($arg1 == "5") {
            if ($this->student -> constraints["CDAD"] == false) {
                $this->student -> constraints["CDAD"] = true;
            } else {
                $this->student -> constraints["CDAD"] = false;
            }
        }
        if ($arg1 == "6") {
            if ($this->student -> constraints["CCEA"] == false) {
                $this->student -> constraints["CCEA"] = true;
            } else {
                $this->student -> constraints["CCEA"] = false;
            }
        }
        if ($arg1 == "7") {
            if ($this->student -> constraints["CADT"] == false) {
                $this->student -> constraints["CADT"] = true;
            } else {
                $this->student -> constraints["CADT"] = false;
            }
        }
        if ($arg1 == "8") {
            if ($this->student -> constraints["CSTS"] == false) {
                $this->student -> constraints["CSTS"] = true;
            } else {
                $this->student -> constraints["CSTS"] = false;
            }
        }

        $this->student -> pushToSession();
    }


    public function returnClickableSearch($arg1 = " ")
    {
        $this -> startRow();
        if (($arg1 != "")&&(strlen($arg1) > 2)) {
            $thing = $this->student -> db -> returnCourses(false, $arg1);

            echo "
           <table class='standartLongTable'>
             <tbody>";
            for ($x = 0; $x < sizeof($thing); $x++) {
                $char = '"';
                echo "
           <tr onclick='processRows(".$char.$thing[$x][1].$char.")>
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
