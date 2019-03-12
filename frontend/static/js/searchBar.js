///////////////////////////////////////////
// Handles the live search functionality //
///////////////////////////////////////////

function fill(Value) {
  $('#search').val(Value);
  $('#display').hide();
}

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this,
      args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function() {
      callback.apply(context, args);
    }, ms || 0);
  };
}

$(document).ready(function() {
  $('#search').keyup(delay(function(e) {
    var name = $('#search').val();
    if (name == "") {


      $.ajax({

        type: "POST",
        url: "./frontend/contentCreatorQuery.php",
        data: {

          returnAllCourses: name
        },

        success: function(html) {

          $("#livesearch").html(html).show();
        }
      });
    } else {

      $.ajax({

        type: "POST",

        url: "./frontend/contentCreatorQuery.php",

        data: {

          search: name
        },

        success: function(html) {

          $("#livesearch").html(html).show();
        }
      });
    }
  }, 300));

  $('#search2').keyup(delay(function(e) {

    var name = $('#search2').val();

    if (name == "") {


      $.ajax({

        type: "POST",

        url: "./frontend/contentCreatorQuery.php",

        data: {

          returnClickableFilteredCourses: name
        },

        success: function(html) {

          $("#livesearch").html(html).show();
        }
      });
    } else {

      $.ajax({

        type: "POST",

        url: "./frontend/contentCreatorQuery.php",

        data: {

          returnClickableSearch: name
        },

        success: function(html) {

          $("#livesearch").html(html).show();
        }
      });
    }
  }, 300));

  $('#search3').keyup(delay(function(e) {

    var name = $('#search2').val();

    if (name == "") {


      $.ajax({

        type: "POST",

        url: "./frontend/contentCreatorQuery.php",

        data: {

          returnNewClickableFilteredCourses: name
        },

        success: function(html) {

          $("#livesearch").html(html).show();
        }
      });
    } else {

      $.ajax({

        type: "POST",

        url: "./frontend/contentCreatorQuery.php",

        data: {

          returnClickableSearch: name
        },

        success: function(html) {

          $("#livesearch").html(html).show();
        }
      });
    }
  }, 300));
});
