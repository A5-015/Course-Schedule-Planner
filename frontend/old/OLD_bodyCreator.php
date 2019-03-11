<?php
require_once("bodyItemCreator.php");

// Generating the body in terms of HTML
echo "
      <body>
        <table class='skelethon'>
          <tr>
            <td>
              <table class='skelethon'>
                <tbody>";

                  // Upper part of the body
                  // Insert logo depending on the step
                  if (($step == 0)||($step == 1)||($step == 2)||($step == 3)||($step == 4)) {

                    insertBodyItem($student, "logo");

                  }

                  // Middle part of the body
                  // Step 0
                  if ($step == 0) {

                    insertBodyItem($student, "searchBox", "search");
                    insertBodyItem($student, "submitButton", "SubmitButton", "Make your course schedule");

                  // Step 1
                  } else if ($step == 1) {

                    insertBodyItem($student, "majorSelectionPageList");
                    insertBodyItem($student, "submitButton", "jumpToStep3", "I haven't decided my major yet");

                  // Step 2
                  } else if ($step == 2) {

                    insertBodyItem($student, "studentInformation");
                    insertBodyItem($student, "bodyText", "Please select courses that you have already completed from your major requirements");
                    insertBodyItem($student, "submitButton", "SubmitButton", "Continue");
                    insertBodyItem($student, "requirementSelectionList", "major");

                  // Step 3
                  } elseif ($step == 3) {

                    insertBodyItem($student, "studentInformation");
                    insertBodyItem($student, "alreadyTakenClasses");
                    insertBodyItem($student, "bodyText", "Please select courses that you have already completed");
                    insertBodyItem($student, "searchBox", "search2");
                    insertBodyItem($student, "checBox");
                    //insertBodyItem($student, "formatting", "<hr style='height:8px; visibility:hidden;' />");
                    insertBodyItem($student, "submitButton", "SubmitButton", "Continue");

                  // Step 4
                  } elseif ($step == 4) {

                    insertBodyItem($student, "studentInformation");
                    insertBodyItem($student, "alreadyTakenClasses");
                    insertBodyItem($student, "selectedClasses");
                    insertBodyItem($student, "bodyText", "Please select courses you want to take");
                    insertBodyItem($student, "searchBox", "search2");
                    insertBodyItem($student, "checBox");
                    insertBodyItem($student, "submitButton", "SubmitButton", "Print Your Schedule");

                  // Step 5
                  } else if ($step == 5) {
                    insertBodyItem($student, "lastPageText");
                    insertBodyItem($student, "calendar", "iframeFinalPage");
                  }


                  // Bottom part of the body
                  if ($step == 0) {

                    insertBodyItem($student, "liveSearch", "homePageRegularList");

                  } else if ($step == 3) {

                    insertBodyItem($student, "liveSearch", "requirementSelectionList", "filteredAll");

                  } else if ($step == 4) {

                    insertBodyItem($student, "liveSearch", "requirementSelectionList", "filteredAllForNewSelection");

                  }


                  // Footer part of the body
                  if($step != 0){
                    insertBodyItem($student, "reset");
                  }

echo "
                </tbody>
              </table>
            </td>
            <td>";

              if ($step == 4) {
                echo "<table>";
                  insertBodyItem($student, "calendar", "iframe");
                echo "</table>";
              }

echo "
            </td>
          </tr>
        </table>
      </body>
  </html>";
?>
