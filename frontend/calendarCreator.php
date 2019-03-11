<?php

session_start();

echo '
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" media="print, screen">
  <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css" media="print, screen">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.22.1/min/moment-with-locales.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-touchswipe@1.6.18/jquery.touchSwipe.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/eonasdan-bootstrap-datetimepicker@4.17.47/build/js/bootstrap-datetimepicker.min.js"></script>

  <script src="static/js/jquery-calendar.js"></script>
  <link rel="stylesheet" href="static/css/jquery-calendar.css" media="print, screen">
  <title>Calendar</title>
</head>

<body>
<div class="container-fluid px-4">
  <div class="row">
    <div class="col-xs-12">
      <div id="calendar"></div>
    </div>
  </div>
</div>

  <script>
    $(document).ready(function() {

      moment.locale("en");
      var now = moment();

      /**
       * Many events
       */
      var events = [

      ';

        $thing = $_SESSION["selectedCourses"];
        $earliestHour = 9;
        $latestHour = 18;

        foreach($thing as $class){
           foreach($class as $individualDates){

               $startTimeHour = $individualDates['startHour'];
               $startTimeMinute = $individualDates['startMinute'];
               $endTimeHour = $individualDates['endHour'];
               $endTimeMinute = $individualDates['endMinute'];

               $title = $individualDates['title'];
               $description = $individualDates['description'];

               $description = utf8_encode($description);

               $dayOfTheWeek = $individualDates['day'];

               $id = $individualDates['peopleSoftID'];

               echo '
               {
                 start: now.startOf("week").add('.$startTimeHour.', "h").add('.$dayOfTheWeek.', "days").add('.$startTimeMinute.', "m").format("X"),
                 end: now.startOf("week").add('.$endTimeHour.', "h").add('.$dayOfTheWeek.', "days").add('.$endTimeMinute.', "m").format("X"),
                 title: "'.$title.'",
                 content: "Course ID: '.$id.'<br>'.$description.'",
                 category: "'.$id.'"
               }
               ';

                echo ',';
        /*
               if($x != ($last - 1)){
                 echo ',';
               }
        */

   }
 }

echo '
    ];

      /**
       * Init the calendar
       */
      var calendar = $("#calendar").Calendar({
        locale: "en",
        view: "week",


        weekday: {
          timeline: {
            intervalMinutes: 30,
            fromHour: '.$earliestHour.',
            toHour: '.$latestHour.'
          }
        },

        categories: {
          enable: false
        },
        events: events
      }).init();

    });
  </script>
</body>

</html>';

?>
