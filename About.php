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
    <div class="row justify-content-between header">
        <div class="col-auto p">
            <p>[DISCOUNT CODE]</p>
        </div>
        <div class="col-auto">
            <div class="row justify-content-between">
                <div class="col-auto">
                    <?php
                        if(isset($_SESSION["username"]) && $_SESSION["username"] != "") {
                            echo "<label class='p' style='float: left; font-weight: bold'>Welcome, <a href='Profile.php'>".$_SESSION["username"]."</a></label>";
                            echo '<ul class="notype">
                                    <form method="post" name="signoutform" action="<?php echo $_SERVER[\'PHP_SELF\']; ?> ">
                                        <a href="?actie=signout" type="submit"><li class="lis rounded"><b><img src="img/signout.png" alt="logout" class="img-fluid sign"/>SIGN OUT</b></li></a>
                                    </form
                            </ul>';
                        }
                        else {
                            echo '<ul class="notype">
                                    <a href="" data-toggle="modal" data-target="#modalLoginForm"><li class="lis rounded"><b><img src="img/signin.png" alt="signin" class="img-fluid sign"/>SIGN IN</b></li></a>
                                    <a href="" data-toggle="modal" data-target="#modalRegisterForm"><li class="lis rounded"><b><img src="img/signup.png" alt="signup" class="img-fluid sign"/>SIGN UP</b></li></a>
                                </ul>';
                        }
                    ?>
                </div>
                <div class="col-auto">
                    <ul class="notype">
                        <a href="Cart.php?customerid=<?php echo $customid;?>"><li class="margin lis rounded">
                            <img src="img/buy.png" alt="signin" class="img-fluid buy"/>
                        </li></a>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="modalLoginForm" name="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign in</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
       <form id="modalLogForm" name="modalLogForm" class="form-vertical" method="post" action="Home.php">
            <div class="md-form mb-5">
              <i class="fas fa-user prefix grey-text"></i>
              <input type="text" id="user" class="form-control validate" name="user">
              <label data-error="wrong" data-success="right" for="defaultForm-email">Email or Username</label>
            </div>

            <div class="md-form mb-4">
              <i class="fas fa-lock prefix grey-text"></i>
              <input type="password" id="password" class="form-control validate" name="password">
              <label data-error="wrong" data-success="right" for="defaultForm-pass">Password</label>
            </div>

            <span id="userError" style="color: red; font-weight: bold"></span>
            <span id="passwordError" style="color: red; font-weight: bold"></span>

          <div class="modal-footer d-flex justify-content-center">
            <button type="button" class="btn btn-default" name="signin_button" id="signin_button" onclick="submitModalLoginForm()">Sign in</button>
            <button type="button" name="loadsave" id="btnloadSignin" class="btn btn-default" style="display: none;">
                <div class="spinner-border text-light" role="status" style="display: none; width: 1.3rem; height: 1.3rem;" id="loadSignin">
                </div>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="modalSignupForm" name="modalSignupForm" class="form-vertical" method="post" action="Home.php">
        <div class="md-form mb-5">
          <i class="fas fa-user prefix grey-text"></i>
          <input type="text" name="username" id="username" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-pass">Username</label>
        </div>
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" name="email" id="email" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-email">Email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" name="pass" id="pass" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-pass">Password</label>
        </div>
          
        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" name="passconfirm" id="passconfirm" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-pass">Confirm Password</label>
        </div>
        
        <span id="usernameError" style="color: red; font-weight: bold"></span>
        <span id="emailError" style="color: red; font-weight: bold"></span>
        <span id="passError" style="color: red; font-weight: bold"></span>
            
        <div class="modal-footer d-flex justify-content-center" style="margin-top: 20px;">
            <button type="button" class="btn btn-deep-orange" name="signup_button" id="signup_button" onclick="submitModalSignupForm()">Sign up</button>
            <button type="button" name="loadsave" id="btnloadSignup" class="btn btn-deep-orange" style="display: none;">
                <div class="spinner-border text-light" role="status" style="display: none; width: 1.3rem; height: 1.3rem;" id="loadSignup">
                </div>
            </button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
    <div class="row justify-content-center title">
      <img src="img/banner1.png" class="img-fluid banner" alt="Responsive image">
    </div>
    <div class="row justify-content-center menurow">
        <div class="col-auto" id="menu">
            <ul class="notype">
                <a href="Home.php"><li class="menu">HOME</li></a>
                <a href="Pricing.php"><li class="menu">PRICING</li></a>
                <a href="About.php"><li class="menu">ABOUT ME</li></a>
                <a href="Terms.php"><li class="menu">TERMS OF SERVICE</li></a>
                <a href="Contact.php"><li class="menu">CONTACT</li></a>
            </ul>
        </div>
            <!--Navbar-->
            <nav class="navbar lighten-4" id="navigationbar">

                <!-- Collapse button -->
                <button class="navbar-toggler navbar-toggler-icon" type="button" data-toggle="collapse" data-target="#navbarSupportedContent1"
                aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"><span class="dark-blue-text"><i
                class="fas fa-bars fa-1x"></i></span></button>

                  <!-- Collapsible content -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent1">

                    <!-- Links -->
                    <ul class="navbar-nav notype">
                        <a class="nav-link" href="Home.html"><li class="nav-item menu">
                            HOME
                        </li></a>
                        <a class="nav-link" href="Pricing.html"><li class="nav-item menu">
                            PRICING
                        </li></a>
                        <a class="nav-link" href="About.html"><li class="nav-item menu">
                            ABOUT ME
                        </li></a>
                        <a class="nav-link" href="Terms.html"><li class="nav-item menu">
                            TERMS OF SERVICE
                        </li></a>
                        <a class="nav-link" href="Contact.html"><li class="nav-item menu">
                            CONTACT
                        </li></a>
                    </ul>
                    <!-- Links -->

                </div>
                <!-- Collapsible content -->

            </nav>
            <!--/.Navbar-->
    </div>
    <div class="row examp">
        <h1>[Examples or products]</h1>
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
