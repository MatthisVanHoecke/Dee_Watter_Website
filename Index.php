<?php
  include 'code.php';
?>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Dee Watter's Webshop</title>
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

<body style='height: auto'>

  <!-- Start your project here-->
    <?php include "standard.php"; ?>
  <div class="modal fade" id="modalLogError" tabindex="-1" role="dialog" aria-labelledby="modalLogError" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
          <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
        </svg>
        <h5 class="modal-title" id="exampleModalLongTitle">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label>Please log in first!</label>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalImage" tabindex="-1" role="dialog" aria-labelledby="modalImage" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" id="modalimg" class="img-thumbnail">
      </div>
    </div>
  </div>
</div>
    <div class="row justify-content-center examp" style="height: auto">
      <div class="col-md-8 profile" style="padding-bottom: 10px;">
        <div class="row justify-content-center">
          <h1>Examples</h1>
        </div>
        <div class="row justify-content-center" style="margin-top: 10px;">
          <div class="col-md-4">
            <img src="img/Cupids_Bow.png" alt="1" class="img-thumbnail clickable">
          </div>
          <div class="col-md-4">
            <img src="img/EseDqWEW8AEyzXL.png" alt="2" class="img-thumbnail clickable">
          </div>
          <div class="col-md-4">
            <img src="img/will_wb.png" alt="3" class="img-thumbnail clickable">
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-4">
            <img src="img/villain_deku.png" alt="1" class="img-thumbnail clickable">
          </div>
          <div class="col-md-4">
            <img src="img/head-shot.jpg" alt="2" class="img-thumbnail clickable">
          </div>
          <div class="col-md-4">
            <img src="img/half-body.jpg" alt="2" class="img-thumbnail clickable">
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-6">
            <img src="img/background2.png" alt="1" class="img-thumbnail clickable">
          </div>
          <div class="col-md-6">
            <img src="img/bg2.png" alt="2" class="img-thumbnail clickable">
          </div>
        </div>
      </div>
    </div>

  <!-- /Start your project here-->

  <!-- SCRIPTS -->
      <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.0.min.js">
  </script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
<script type="text/javascript" src="js/deescript.js"></script>
<script>
    window.onload = function() {
      load();

      for(var i = 0; i < document.getElementsByClassName("clickable").length; i++) {
        document.getElementsByClassName("clickable")[i].addEventListener("click", function() {
          document.getElementById("modalimg").src = this.src;
          $("#modalImage").modal("show");
        });
      }
    };
  </script>
</body>

</html>
