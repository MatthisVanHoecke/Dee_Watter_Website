<?php
    include 'code.php';
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <titleDee Watter's Webshop</title>
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

<body style="height: 100%">

  <!-- Start your project here-->
  <?php include "standard.php"; ?>
    
  <div class="row justify-content-center examp" style="height: auto">
        <div class="col-md-auto d-flex justify-content-center">
            <div class="card items zoom">
                <img src="img/head-shot.jpg" alt="head-shot" class="card-img-top rounded">
                <div class="card-body">
                    <h1 class="card-title">Head</h1>
                    <p class="card-text">An artwork containing the character's face only.</p>
                    <p class="card-text">$14</p>
                    <a href="Article.php?article=Head" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-auto d-flex justify-content-center">
            <div class="card items zoom">
                <img src="img/half-body.jpg" alt="body-shot" class="card-img-top rounded">
                <div class="card-body">
                    <h1 class="card-title">Body</h1>
                    <p class="card-text">An artwork containing the face and upper-body of the character.</p>
                    <p class="card-text">$16</p>
                    <a href="Article.php?article=HalfBody" class="stretched-link"></a>
                </div>
            </div>
        </div> 
        <div class="col-md-auto d-flex justify-content-center">
            <div class="card items zoom">
                <img src="img/Fullbody.jpg" alt="fullbody-shot" class="card-img-top rounded">
                <div class="card-body">
                    <h1 class="card-title">Full</h1>
                    <p class="card-text">An artwork containing the face and entire body of the character.</p>
                    <p class="card-text">$22</p>
                    <a href="Article.php?article=FullBody" class="stretched-link"></a>
                </div>
            </div>
        </div> 
</div>

  <!-- /Start your project here-->

  <!-- SCRIPTS -->

    <script type="text/javascript" src="js/deescript.js"></script>
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.0.min.js">
  </script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script type="text/javascript">
    window.onload = load();
</script>
</body>

</html>
