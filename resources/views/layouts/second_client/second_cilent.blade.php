<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ticket Booking</title>
  <link rel="stylesheet" type="text/css" href="assets/css/style-starter.css">
  <link rel="stylesheet" href="https://npmcdn.com/flickity@2/dist/flickity.css">
  <link rel="stylesheet" type="text/css" href="assets/css/progress.css">

  <link rel="stylesheet" type="text/css" href="assets/css/ticket-booking.css">

  <!-- ..............For progress-bar............... -->
  <link rel="stylesheet" type="text/css" href="assets/css/e-ticket.css">

  <link rel="stylesheet" type="text/css" href="assets/css/payment.css" />
  <link href="https://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,700" rel="stylesheet">
</head>

<body>
    @yield('content')
</body>
<script>
  let prevId = "1";

  window.onload = function () {
    document.getElementById("screen-next-btn").disabled = true;
  }

  function timeFunction() {
    document.getElementById("screen-next-btn").disabled = false;
  }

  function myFunction(id) {
    document.getElementById(prevId).style.background = "rgb(243, 235, 235)";
    document.getElementById(id).style.background = "#df0e62";
    prevId = id;
  }
</script>

<script src="https://npmcdn.com/flickity@2/dist/flickity.pkgd.js"></script>
<script type="text/javascript" src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js'>
</script>
<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src="assets/js/theme-change.js"></script>

<script type="text/javascript" src="assets/js/ticket-booking.js"></script>

</html>