<!DOCTYPE html>

<?php

    include 'code.php';

    if($_SESSION["username"] == "" && !isset($_GET["actie"]) && $_GET["actie"] != "signout") {
        header("location: accountError.php");
    }

    if(mysqli_connect_errno()) {
        trigger_error("fout bij verbinding: ".$mysqli->error);
    }
    else {
        
        $sql = "SELECT Isadmin FROM tblCustomers WHERE Username = ?";

        if($stmt = $mysqli->prepare($sql)) {
            
            $stmt->bind_param('s', $username);
            
            $username = $_SESSION["username"];
            
            if(!$stmt->execute()) {
                echo "het uitvoeren van de query is mislukt: ".$stmt->error." in query ".$sql;
            }
            else {
            
                $stmt->bind_result($admin);
                $stmt->fetch();

                if($admin == 0) {
                    echo '<style>#adminpage {display:none;}</style>';
                }
                
                $stmt->close();
            }
        }
        else {
            echo "error";
        }
    }
    

    if(isset($_POST["change_name"]) && isset($_POST["changeUsername"]) && $_POST["changeUsername"] != "") {
        
        if(mysqli_connect_errno()) {
            trigger_error("fout bij verbinding: ".$mysqli->error);
        }
        else {
            
            $sql = "SELECT Username FROM tblCustomers WHERE Username = ?";
            
            $count = 0;
            if($stmt = $mysqli->prepare($sql)) {
                
                $stmt->bind_param('s', $username);
                
                $username = $_POST["changeUsername"];
                
                if(!$stmt->execute()) {
                    echo "het uitvoeren van de query is mislukt: ".$stmt->error." in query ".$sql;
                }
                else {

                    while($stmt->fetch()) {
                        $count++;
                    }
                }
            }
            if($count == 0) {
                $sql = "
                UPDATE tblCustomers
                SET Username = ?
                WHERE Username = ?"
                ;

                if($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param('ss', $changename, $name);

                    $changename = $mysqli->real_escape_string($_POST["changeUsername"]);
                    
                    $name = $_SESSION['username'];

                    $_SESSION['username'] = $_POST["changeUsername"];
                    $stmt->execute();
                        
                    $stmt->close();
                }
                else {
                    echo "er zit een fout in de query";
                }
            }
        }
    }
    if(isset($_POST["change_pass"]) && isset($_POST["changePassword"]) && $_POST["changePassword"] != "") {
        
        if(mysqli_connect_errno()) {
            trigger_error("fout bij verbinding: ".$mysqli->error);
        }
        else {
            $sql = "
            UPDATE tblCustomers
            SET Password = ?
            WHERE Username = ?"
            ;

            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('ss', $pass, $username);

                $pass = $mysqli->real_escape_string($_POST["changePassword"]);
                
                $username = $_SESSION['username'];
                
                $stmt->execute();

                $stmt->close();
            }
            else {
                echo "er zit een fout in de query";
            }
            
        }
    }
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
                        <a class="nav-link" href="Home.php"><li class="nav-item menu">
                            HOME
                        </li></a>
                        <a class="nav-link" href="Pricing.php"><li class="nav-item menu">
                            PRICING
                        </li></a>
                        <a class="nav-link" href="About.php"><li class="nav-item menu">
                            ABOUT ME
                        </li></a>
                        <a class="nav-link" href="Terms.php"><li class="nav-item menu">
                            TERMS OF SERVICE
                        </li></a>
                        <a class="nav-link" href="Contact.php"><li class="nav-item menu">
                            CONTACT
                        </li></a>
                    </ul>
                    <!-- Links -->

                </div>
                <!-- Collapsible content -->

            </nav>
            <!--/.Navbar-->
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-5 profile">
            <h1 style="text-align: center">Profile</h1>
            <form name="form1" class="margin" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
                
                <table style="width: 100%">
                    <tr>
                        <td style="font-size: 20px; font-weight: bold;">Username:</td>
                        <td><input type=text name="changeUsername"></td>
                        <td><button type="submit" class="btn btn-default" name="change_name">change username</button></td>
                    </tr>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold;">Password:</td>
                        <td><input type="password" name="changePassword"></td>
                        <td><button type="submit" class="btn btn-default" name="change_pass">change password</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="row justify-content-center" id="adminpage">
        <div class="col-md-5 profile">
            <h1 style="text-align: center">Admin</h1>
            
            <form name="frmcustomer" method="post" action="Orders.php">
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-default" name="getorders">Get Orders</button>
                </div>
            </form>

            <form name="frmcustomer" method="post" style="margin-top: 20px;" action="<?php echo $_SERVER['PHP_SELF']?>">
                <label>Username or email:</label>
                <input type="text" name="customer">
                <button type="submit" class="btn btn-default" name="search">search</button>
            </form>
            <table class="tab" style="background-color: white;">
                <tr>
                    <td>
                        <b>ID</b>
                    </td>
                    <td>
                        <b>Name</b>
                    </td>
                    <td>
                        <b>Email</b>
                    </td>
                    <td>
                        <b>Edit</b>
                    </td>
                </tr>
                </table>
                <div id="scrollbar" style="margin-bottom: 15px;">
                <table class="tab" style="background-color: white;">
            <?php
                if(mysqli_connect_errno()) {
                    trigger_error("Error when connecting: ".$mysqli->error);
                }
                else {
                    if(isset($_POST["search"]) && isset($_POST["customer"]) && $_POST["customer"] != "") {
                        if(mysqli_connect_errno()) {
                            trigger_error('fout bij verbinding'.$mysqli->error);
                        }
                        
                        if(strpos($_POST["customer"], '@') !== false) {
                            $sql = "
                            SELECT CustomerID, Username, Email
                            FROM tblCustomers
                            WHERE Email LIKE ?
                            ORDER BY CustomerID
                            ";  
                        }
                        else {
                            $sql = "
                            SELECT CustomerID, Username, Email
                            FROM tblCustomers
                            WHERE Username LIKE ?
                            ORDER BY CustomerID
                            ";  
                        }

                        if($stmt = $mysqli->prepare($sql)) {
                            $stmt->bind_param('s', $zoek);

                            $zoek = $_POST["customer"]."%";
                            if(!$stmt->execute()) {
                                echo "het uitvoeren is mislukt: ".$stmt->error."in query ".$sql;
                            }
                            else {
                                $stmt->bind_result($id,$naam,$email);

                                while($stmt->fetch()) {
                                    echo "<tr><td>".$id."</td><td>".$naam."</td><td>".$email."</td><td>";
                    ?>
                                <form name="form1" method="post" action="EditUser.php?action=edit&customerid=<?php echo $id;?>">
                                    <input type="submit" name="btnedit" id="action" value="Edit">
                                </form>
                    <?php
                                    echo "</td></tr>";
                                }
                            }
                            $stmt->close();
                        }
                        else {
                            echo "er is een fout in query: ".$mysqli->error;
                        }
                    }
                }
            ?>
            </table>
            </div>
        </div>
    </div>
    

  <!-- /Start your project here-->

  <!-- SCRIPTS -->
  <script type="text/javascript">

</script>
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
</body>

</html>
