
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
   $('#search').keyup(delay(function (e) {
       //Assigning search box value to javascript variable named as "name".
       var name = $('#search').val();
       //Validating, if "name" is empty.
       if (name == "") {

         // Fill the space if the search term was deleted
         $.ajax({
             //AJAX type is "Post".
             type: "POST",
             //Data will be sent to "ajax.php".
             url: "./frontend/contentCreatorQuery.php",
             //Data, that will be sent to "ajax.php".
             data: {
                 //Assigning value of "name" into "search" variable.
                 returnAllCourses: name
             },
             //If result found, this funtion will be called.
             success: function(html) {
                 //Assigning result to "display" div in "search.php" file.
                 $("#livesearch").html(html).show();
             }
         });
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

   $('#search2').keyup(delay(function (e) {
       //Assigning search box value to javascript variable named as "name".
       var name = $('#search2').val();
       //Validating, if "name" is empty.
       if (name == "") {

         // Fill the space if the search term was deleted
         $.ajax({
             //AJAX type is "Post".
             type: "POST",
             //Data will be sent to "ajax.php".
             url: "./frontend/contentCreatorQuery.php",
             //Data, that will be sent to "ajax.php".
             data: {
                 //Assigning value of "name" into "search" variable.
                 returnClickableFilteredCourses: name
             },
             //If result found, this funtion will be called.
             success: function(html) {
                 //Assigning result to "display" div in "search.php" file.
                 $("#livesearch").html(html).show();
             }
         });
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
                   returnClickableSearch: name
               },
               //If result found, this funtion will be called.
               success: function(html) {
                   //Assigning result to "display" div in "search.php" file.
                   $("#livesearch").html(html).show();
               }
           });
       }
   }, 300));

   $('#search3').keyup(delay(function (e) {
       //Assigning search box value to javascript variable named as "name".
       var name = $('#search2').val();
       //Validating, if "name" is empty.
       if (name == "") {

         // Fill the space if the search term was deleted
         $.ajax({
             //AJAX type is "Post".
             type: "POST",
             //Data will be sent to "ajax.php".
             url: "./frontend/contentCreatorQuery.php",
             //Data, that will be sent to "ajax.php".
             data: {
                 //Assigning value of "name" into "search" variable.
                 returnNewClickableFilteredCourses: name
             },
             //If result found, this funtion will be called.
             success: function(html) {
                 //Assigning result to "display" div in "search.php" file.
                 $("#livesearch").html(html).show();
             }
         });
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
                   returnClickableSearch: name
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
