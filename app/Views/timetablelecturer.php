



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
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"> -->
        <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
        <link href='https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css' rel='stylesheet'>
        <!-- <link rel="stylesheet" href="./stundenplanTest//public/bootswatch-master/dist/sketchy/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"> -->

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.0/main.min.css">
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.0/main.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.0/locales-all.min.js"></script>
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script> -->
         <script src='https://unpkg.com/popper.js/dist/umd/popper.min.js'></script>
        <script src="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js"></script> 








    <style>
        body {
            padding-top: 5rem;
            /* background-color: gray; */
        }

        .btn-group-xs > .btn, .btn-xs {
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
    box-shadow: 0 0 2px rgba(0,0,0,0.5);
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

  /* #calendar { */
    /* background-color: whitesmoke; */
    /* opacity: 01; */
  /* } */



    </style>


<script>
    function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  // return 'black'
  return color;
}

// function getRandomColor() {
//   color = "hsl(" + Math.random() * 360 + ", 100%, 75%)";
//   return color;
// }
            

document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    nowIndicator: true,
    themeSystem: 'bootstrap',
//     eventClick: function(info) {
//     $("#exampleModalLabel").text('' +info.event.title);
//     $("#modal-body").text('' +info.event.extendedProps.description);
//     $('#exampleModal').modal('toggle');
// // $('#exampleModal').modal('show');
// },
eventDidMount: function(info) {
        var tooltip = new Tooltip(info.el, {
          title: info.event.extendedProps.description,
          placement: 'top',
          trigger: 'hover',
          container: 'body'
        });
      },
        events: [<?= $events ?>,
    {
      title: 'Pause',
      daysOfWeek: [1,2,3,4,5],
      startTime: '09:30',
      endTime: '09:45',
      displayEventEnd: true,
      backgroundColor: getRandomColor(),
      description: 'Pause'
    },
    {
      title: 'Mittag',
      daysOfWeek: [1,2,3,4,5],
      startTime: '11:15',
      endTime: '12:00',
      displayEventEnd: true,
      backgroundColor: getRandomColor(),
      description: 'Mittag'
    },
    {
      title: 'Pause',
      daysOfWeek: [1,2,3,4,5],
      startTime: '13:30',
      endTime: '13:45',
      displayEventEnd: true,
      backgroundColor: getRandomColor(),
      description: 'Pause'
    },
],
    views: {
      timeGridWeek: { // name of view
    //   titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' },
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


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>











