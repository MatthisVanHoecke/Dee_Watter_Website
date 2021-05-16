<?php
  include 'code.php';
    
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Material Design Bootstrap</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="css/mdb.min.css" rel="stylesheet">
  <!-- Your custom styles (optional) -->
  <link href="css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="css/deestyle.css">
</head>

<body>

  <!-- Start your project here-->
    <?php include "standard.php"; ?>
    
    <div class="row justify-content-center examp" style="height: 100%">
        <div class="col-md-6 profile" style="background-color: white; padding-bottom: 20%;">
            <div class="terms">
                <h5><b>Read these Terms and Conditions before requesting any services:</b></h5>
                <ul>
                    <li>I have the right to refuse service if it includes anything I'm uncomfortable with</li>
                    <li>I won't do business with anyone younger than 13</li>
                    <li>Payment will be done before I start working, and only through PayPal</li>
                    <li>The artwork the client receives will not be switching ownership, so if they want to post it or use it anywhere they need to:
                        <ul class="numbers">
                            <li style="list-style-type: decimal;">State that the artwork is mine, and done by me</li>
                            <li>Provide my contact information (even if it's just my tag) </li>
                            <li>Make sure that my signature/ watermark is visible on the drawing</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

  <!-- /Start your project here-->

  <!-- SCRIPTS -->
  <script type="text/javascript">
    toggle();
    window.onresize = function() {
        toggle();
    }
    function toggle() {
        if (window.innerWidth > 1020) {
            document.getElementById('navigationbar').style.display = 'none';  
            document.getElementById('menu').style.display = 'block'; 
        }
        else {
            document.getElementById('navigationbar').style.display = 'block';
            document.getElementById('menu').style.display = 'none'; 
        }    
    }
</script>
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.0.min.js">
  </script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
</body>

</html>
