<?php
    include "code.php";

    if(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["customerid"])) {
        
        if(mysqli_connect_errno()) {
            trigger_error("Error when connecting: ".$mysqli->error);
        }
        else {
            $sql = "
            SELECT CustomerID, Username, Email FROM tblCustomers
            WHERE CustomerID = ?
            ";

            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('i', $customerid);

                $customerid = $mysqli->real_escape_string($_GET["customerid"]);

                if(!$stmt->execute()) {
                    echo "The execution of the query has failed: ".$stmt->error." in query ".$sql;
                }
                else {
                    $stmt->bind_result($id,$name,$email);
                    
                    $stmt->fetch();
                    
                }
                $stmt->close();
            }
            else {
                echo "There's an error in the query";
            }
        }
    }
    
    if(isset($_POST["name"]) && $_POST["name"] != "" && isset($_POST["email"]) && $_POST["email"] != "") {
        $sql = "
        UPDATE tblCustomers
        SET Username = ?, Email = ?
        WHERE CustomerID = ?"
        ;

        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('ssi', $name, $email, $id);

            $name = $mysqli->real_escape_string($_POST["name"]);

            $email = $mysqli->real_escape_string($_POST["email"]);
            
            $id = $mysqli->real_escape_string($_GET["customerid"]);

            if(!$stmt->execute()) {
                echo "failed to save";
            }

            $stmt->close();
        }
        else {
            echo "er zit een fout in de query";
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
            <p>[POTENTIAL DISCOUNT CODE]</p>
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
                        <a href="#"><li class="margin lis rounded">
                            <img src="img/buy.png" alt="signin" class="img-fluid buy"/>
                        </li></a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" id="defaultForm-email" class="form-control validate">
          <label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="defaultForm-pass" class="form-control validate">
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Your password</label>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-default">Login</button>
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
      <div class="modal-body mx-3">
        <div class="md-form mb-5">
          <i class="fas fa-user prefix grey-text"></i>
          <input type="text" id="orangeForm-name" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-name">Your name</label>
        </div>
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" id="orangeForm-email" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-email">Your email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="orangeForm-pass" class="form-control validate">
          <label data-error="wrong" data-success="right" for="orangeForm-pass">Your password</label>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-deep-orange">Sign up</button>
      </div>
    </div>
  </div>
</div>
    <div class="row justify-content-center title">
        <h1>Dee Watter</h1>
    </div>
    <div class="row justify-content-center menurow">
        <div class="col-auto" id="menu">
            <ul class="notype">
                <a href="Home.php"><li class="menu">HOME</li></a>
                <a href="Pricing.html"><li class="menu">PRICING</li></a>
                <a href="About.html"><li class="menu">ABOUT ME</li></a>
                <a href="Terms.html"><li class="menu">TERMS OF SERVICE</li></a>
                <a href="Contact.html"><li class="menu">CONTACT</li></a>
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
                    <ul class="navbar-nav">
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
    
    <div class="row justify-content-center">
        <div class="col-md-5 profile">
            <?php echo "<h1 style='text-align: center;'>".$id."</h1>";?>
            <form name="form1" id="form1" class="margin" method="post" action="<?php echo $_SERVER['PHP_SELF']."?action=edit&customerid=".$id;?>">
                <h3 style="font-weight:bold;">Edit User</h3>
                <table class="edittab">
                    <tr>
                        <td>
                            <label style="font-size: 20px; font-weight: bold;">Name: </label>
                        </td>
                        <td>
                            <input type="text" name="name" id="name" value="<?php echo $name;?>">
                        </td>
                        <td style="width: 50%">
                            <span id="nameError" style="color: red; font-weight: bold;"></span>   
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style="font-size: 20px; font-weight: bold;">Email: </label>
                        </td>
                        <td>
                            <input type="text" name="email" id="email" value="<?php echo $email;?>">
                        </td>
                        <td style="width: 50%">
                            <span id="emailError" style="color: red; font-weight: bold;"></span>
                        </td>
                    </tr>
                </table>
                <h3 style="font-weight:bold;">Edit Order</h3>
                <div class="row justify-content-center" style="width: 100%">
                    <button type="button" name="save" id="save" class="btn btn-default" onclick="submitForm1()">Save</button>
                </div>
            </form>
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
      
    function submitForm1() {
        var ok = true;
        
        if(document.getElementById('name').value == "") {
            document.getElementById('nameError').innerHTML = "*Please fill in the user's name!";
            ok = false;
        }
        else {
            if(document.getElementById('name').value.indexOf('\'') >= 0 || document.getElementById('name').value.indexOf('"') >= 0) {
                document.getElementById('nameError').innerHTML = "*Please don't use quotes";
                ok = false;
            }
            else {
                document.getElementById('nameError').innerHTML = "";
            }
        }
        
        if(document.getElementById('email').value == "") {
            document.getElementById('emailError').innerHTML = "*Please fill in the user's email!";
            ok = false;
        }
        else {
            if(document.getElementById('email').value.indexOf('@') < 1 || document.getElementById('email').value.split('@').length-1 > 1) {
                document.getElementById('emailError').innerHTML = "*Invalid email";
                ok = false;
            }
            else {
                if(document.getElementById('email').value.indexOf('\'') >= 0 || document.getElementById('email').value.indexOf('"') >= 0) {
                    document.getElementById('emailError').innerHTML = "*Please don't use quotes";
                    ok = false;
                }
                else {
                    document.getElementById('emailError').innerHTML = "";
                }   
            }
        }
        
        if(ok == true) {
            document.form1.submit();
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