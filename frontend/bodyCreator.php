<?php
// Make sure that BodyItem class is included
require_once("bodyItemCreator.php");

// Generating the body in terms of HTML
// Page content is displayed in a table to show a more organized structure
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

                    $insert -> logo();

                  }

                  // Middle part of the body
                  // Step 0
                  if ($step == 0) {

                    $insert -> searchBox("search");
                    $insert -> submitButton("SubmitButton", "Make your course schedule");

                  // Step 1
                  } else if ($step == 1) {

                    $insert -> majorSelectionPageList();
                    $insert -> submitButton("jumpToStep3", "I haven't decided my major yet");

                  // Step 2
                  } else if ($step == 2) {

                    $insert -> studentInformation();
                    $insert -> bodyText("Please select courses that you have already completed from your major requirements");
                    $insert -> submitButton("SubmitButton", "Continue");
                    $insert -> requirementSelectionList("major");

                  // Step 3
                  } elseif ($step == 3) {

                    $insert -> studentInformation();
                    $insert -> alreadyTakenClasses();
                    $insert -> bodyText("Please select courses that you have already completed");
                    $insert -> searchBox("search2");
                    $insert -> checkBox();
                    $insert -> submitButton("SubmitButton", "Continue");

                  // Step 4
                  } elseif ($step == 4) {

                    $insert -> studentInformation();
                    $insert -> alreadyTakenClasses();
                    $insert -> selectedClasses();
                    $insert -> bodyText("Please select courses you want to take");
                    $insert -> searchBox("search2");
                    $insert -> checkBox();
                    $insert -> submitButton("SubmitButton", "Print Your Schedule");

                  // Step 5
                  } else if ($step == 5) {

                    $insert -> lastPageText();
                    $insert -> calendar("iframeFinalPage");

                  }


                  // Bottom part of the body
                  if ($step == 0) {

                    $insert -> liveSearch("homePageRegularList");

                  } else if ($step == 3) {

                    $insert -> liveSearch("requirementSelectionList", "filteredAll");

                  } else if ($step == 4) {

                    $insert -> liveSearch("requirementSelectionList", "filteredAllForNewSelection");

                  }


                  // Footer part of the body
                  if($step != 0){

                    $insert -> reset();

                  }

echo "
                </tbody>
              </table>
            </td>
            <td>";

              // Handles the side by side division used in the step 4
              if ($step == 4) {
                $insert -> formatting("<table>");
                $insert -> calendar("iframe");
                $insert -> formatting("</table>");
              }

// Close the tags for the table
echo "
            </td>
          </tr>
        </table>
      </body>
  </html>";
?>
