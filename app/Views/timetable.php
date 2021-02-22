<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../../../favicon.ico">

  <title>Stundenplan</title>

  <!-- Bootstrap core CSS -->

  <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">

  <link rel="stylesheet" type="text/css" href="<?= base_url('fontawesome/css/all.min.css') ?>" />



  <link rel="stylesheet" href="<?= base_url('fullcalendar/lib/main.min.css') ?>">
  <script src="<?= base_url('fullcalendar/lib/main.min.js') ?>"></script>
  <script src="<?= base_url('fullcalendar/lib/locales/de.js') ?>"></script>

  <script src='<?= base_url('popper/popper.min.js') ?>'></script>
  <script src="<?= base_url('tooltip/tooltip.min.js') ?>"></script>








  <style>
    body {
      padding-top: 5rem;
      /* background-color: gray; */
      /* background-color: floralwhite; */
    }

    .btn-group-xs>.btn,
    .btn-xs {
      padding: .25rem .4rem;
      font-size: .875rem;
      line-height: .5;
      border-radius: .2rem;
    }




    /*
  i wish this required CSS was better documented :(
  https://github.com/FezVrasta/popper.js/issues/674
  derived from this CSS on this page: https://popper.js.org/tooltip-examples.html
  */

    .popper,
    .tooltip {
      position: absolute;
      z-index: 9999;
      /* background: #FFC107; */
      background: black;
      color: black;
      width: 150px;
      border-radius: 3px;
      box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
      padding: 10px;
      text-align: center;
    }

    .style5 .tooltip {
      background: #1E252B;
      color: #FFFFFF;
      max-width: 200px;
      width: auto;
      font-size: .8rem;
      padding: .5em 1em;
    }

    .popper .popper__arrow,
    .tooltip .tooltip-arrow {
      width: 0;
      height: 0;
      border-style: solid;
      position: absolute;
      margin: 5px;
    }

    .tooltip .tooltip-arrow,
    .popper .popper__arrow {
      border-color: #FFC107;
    }

    .style5 .tooltip .tooltip-arrow {
      border-color: #1E252B;
    }

    .popper[x-placement^="top"],
    .tooltip[x-placement^="top"] {
      margin-bottom: 5px;
    }

    .popper[x-placement^="top"] .popper__arrow,
    .tooltip[x-placement^="top"] .tooltip-arrow {
      border-width: 5px 5px 0 5px;
      border-left-color: transparent;
      border-right-color: transparent;
      border-bottom-color: transparent;
      bottom: -5px;
      left: calc(50% - 5px);
      margin-top: 0;
      margin-bottom: 0;
    }

    .popper[x-placement^="bottom"],
    .tooltip[x-placement^="bottom"] {
      margin-top: 5px;
    }

    .tooltip[x-placement^="bottom"] .tooltip-arrow,
    .popper[x-placement^="bottom"] .popper__arrow {
      border-width: 0 5px 5px 5px;
      border-left-color: transparent;
      border-right-color: transparent;
      border-top-color: transparent;
      top: -5px;
      left: calc(50% - 5px);
      margin-top: 0;
      margin-bottom: 0;
    }

    .tooltip[x-placement^="right"],
    .popper[x-placement^="right"] {
      margin-left: 5px;
    }

    .popper[x-placement^="right"] .popper__arrow,
    .tooltip[x-placement^="right"] .tooltip-arrow {
      border-width: 5px 5px 5px 0;
      border-left-color: transparent;
      border-top-color: transparent;
      border-bottom-color: transparent;
      left: -5px;
      top: calc(50% - 5px);
      margin-left: 0;
      margin-right: 0;
    }

    .popper[x-placement^="left"],
    .tooltip[x-placement^="left"] {
      margin-right: 5px;
    }

    .popper[x-placement^="left"] .popper__arrow,
    .tooltip[x-placement^="left"] .tooltip-arrow {
      border-width: 5px 0 5px 5px;
      border-top-color: transparent;
      border-right-color: transparent;
      border-bottom-color: transparent;
      right: -5px;
      top: calc(50% - 5px);
      margin-left: 0;
      margin-right: 0;
    }

    .tooltip {
      visibility: visible;
      opacity: 1;
      /* background: black; */
    }

    .tooltip-inner {
      background-color: transparent;
    }
  </style>


  <script>
    function getRandomColor() {
      var letters = '0123456789ABCDEF';
      var color = '#';
      for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    }




    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        nowIndicator: true,
        themeSystem: 'bootstrap',
        eventDidMount: function(info) {
          var tooltip = new Tooltip(info.el, {
            title: info.event.extendedProps.description,
            placement: 'top',
            trigger: 'hover',
            container: 'body',
            html: true,
          });
        },
        events: [<?= $currenCourseCopy ?>, <?= $futureCourses ?>, <?= $vacations ?>,
          {
            title: 'Pause',
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '09:30',
            endTime: '09:45',
            displayEventEnd: true,
            backgroundColor: getRandomColor(),
            description: 'Pause'
          },
          {
            title: 'Mittag',
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '11:15',
            endTime: '12:00',
            displayEventEnd: true,
            backgroundColor: getRandomColor(),
            description: 'Mittag'
          },
          {
            title: 'Pause',
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: '13:30',
            endTime: '13:45',
            displayEventEnd: true,
            backgroundColor: getRandomColor(),
            description: 'Pause'
          },
        ],
        views: {
          timeGridWeek: {
            weekends: false,
            slotMinTime: "08:00:00",
            slotMaxTime: "15:15:00",
            slotDuration: '00:15',
          }
        }
      });



      calendar.setOption('locale', 'de');
      calendar.render();
    });
  </script>


</head>

<body>

  <?= view('_navbar') ?>

  <main role="main" class="container">



    <div class="container">
      <div class="row">
        <div class="col-sm-12 offset-sm">
          <div id='calendar' class="pt-5"></div>
        </div>
      </div>
    </div>




  </main>


  <!-- Bootstrap core JavaScriptO
================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="<?= base_url('jquery/jquery-3.5.1.min.js') ?>"></script>

  <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>