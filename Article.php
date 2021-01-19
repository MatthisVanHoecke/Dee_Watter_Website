<?php
    include 'code.php';
    
    if($_SESSION["username"] == "") {
        header("location: accountError.php");
    }

    $articleid = "";
    $articleprice = 0;
    $detail = 0;
    $extrac = 0;
    $customerid = 0;
    $order = 0;
    
    $mysqli = new MySQLi("localhost", "root", "", "webshopphp");

    if(mysqli_connect_errno()) {
        trigger_error("fout bij verbinding: ".$mysqli->error);
    }
    else {
        $sql = "
        SELECT ArticleID, ArticlePrice, Detailed, ExtraCharacter
        FROM tblarticles
        WHERE ArticleName LIKE ?
        ";
        
        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $name);
            
            $name = $_GET["article"];
            if(!$stmt->execute()) {
                echo "the execution has failed: ".$stmt->error." in query ".$sql;
            }
            else {
                $stmt->bind_result($articleid, $articleprice, $detail, $extrac);

                $stmt->fetch();
            }
            $stmt->close();
        }
        
        $sql = "
        SELECT CustomerID
        FROM tblCustomers
        WHERE Username LIKE ?
        ";
        
        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $name);
            
            $name = $mysqli->real_escape_string($_SESSION["username"]);
            if(!$stmt->execute()) {
                echo "the execution has failed: ".$stmt->error." in query ".$sql;
            }
            else {
                $stmt->bind_result($customerid);

                $stmt->fetch();
            }
            $stmt->close();
        }
        else {
            echo "failed";
        }
        
    }


    if(isset($_POST["Description"]) && $_POST["Description"] != "") {
        
        if(mysqli_connect_errno()) {
            trigger_error("fout bij verbinding: ".$mysqli->error);
        }
        else {
            $sql = "
            INSERT INTO tblorders(CustomerID, Date)
            VALUES(?,curdate())
            ";
            if($stmt = $mysqli->prepare($sql)) {

                $stmt->bind_param('i', $customer);

                $customer = $customerid;

                if(!$stmt->execute()) {
                    echo "het uitvoeren is mislukt: ".$stmt->error." in query ".$sql;
                }

                $stmt->close();
            }
            else {
                echo "failed";
            }

            $sql = "
            SELECT OrderID
            FROM tblorders
            WHERE CustomerID LIKE ?
            ";

            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('i', $id);

                $id = $customerid;
                if(!$stmt->execute()) {
                    echo "the execution has failed: ".$stmt->error." in query ".$sql;
                }
                else {
                    $stmt->bind_result($order);

                    $stmt->fetch();
                }
                $stmt->close();
            }
            else {
                echo "failed";
            }
            
            $sql = "
            INSERT INTO tblorderlines(OrderID, ArticleID, Description, File, Detailed, ExtraCharacter, PriceByOrder, Status)
            VALUES(?,?,?,?,?,?,?,?)
            ";
            
            if($stmt = $mysqli->prepare($sql)) {
                
                $stmt->bind_param('iissiids', $orderid, $article, $desc, $file, $detailed, $extra, $price, $status);
                
                $article = $articleid;
                
                $desc = $mysqli->real_escape_string($_POST["Description"]);
                $file = $mysqli->real_escape_string($_POST["upload"]);
                
                $price = $articleprice;
                
                $detailed = 0;
                $extra = 0;
                $status = "In Queue";
                
                $orderid = $order;
                
                if(isset($_POST["detail"])) {
                    $detailed = $mysqli->real_escape_string($_POST["detail"]);
                    $price += $detail;
                }
                
                
                if(isset($_POST["extra"])) {
                    $extra = $mysqli->real_escape_string($_POST["extra"]);
                    $price += $extrac;
                }
                
                if(!$stmt->execute()) {
                    echo "het uitvoeren is mislukt: ".$stmt->error." in query ".$sql;
                }

                $stmt->close();
            }
            else {
                echo "failed";
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
            <?php echo "<h1 style='text-align: center;'>".$_GET["article"]."-shot</h1>";?>
            <form name="form1" id="form1" class="margin" method="post" action="<?php echo $_SERVER['PHP_SELF']."?article=Head";?>">
                <h3 style="font-weight:bold;">Create order</h3>
                <table style="width: 100%">
                    <tr>
                        <td>
                            <label style="font-size: 20px; font-weight: bold;">Description: </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea class="form-control" id="Description" name="Description" rows="5"></textarea>
                        </td>
                        <td style="width: 50%">
                            <span id="descError" style="color: red; font-weight: bold;"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style="font-size: 20px; font-weight: bold;">Reference:</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="file" class="form-control-file" id="upload" name="upload">
                        </td>
                        <td>
                            <span id="uploadError" style="color: red; font-weight: bold;"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style="font-size: 20px; font-weight: bold;">Attributes: </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <ul class="notype">
                                <li>
                                    <input type="checkbox" name="detail" id="detail" onchange="calculate()" value="1">
                                    <label style="font-size: 15px;">Fully detailed</label>
                                </li>
                                <li>
                                    <input type="checkbox" name="extra" id="extra" onclick="calculate()" value="1">
                                    <label style="font-size: 15px;">Extra character</label>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
                <h3 style="font-weight:bold;">Total:</h3>
                <?php if($_GET['article'] == 'Head') { 
                    echo "<label style='font-size: 20px; font-weight: bold;' id='total'>$14</label>";
                }?>
                <div class="row justify-content-center" style="width: 100%">
                    <button type="button" name="save" id="save" class="btn btn-default" onclick="submitForm1()">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <script type="text/javascript">
        var total = 0;
        
        function calculate() {
            
            var parts = window.location.search.substr(1).split("&");
            var $_GET = {};
            for (var i = 0; i < parts.length; i++) {
                var temp = parts[i].split("=");
                $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
            }
            
            if($_GET["article"] == "Head") {
                total = 14;
            }
            
            if(document.getElementById("detail").checked == true) {
                if($_GET["article"] == "Head") {
                    total += 4;
                }   
            }
            if(document.getElementById("extra").checked == true) {
                if($_GET["article"] == "Head") {
                    total += 5;
                }   
            }
            
            
            document.getElementById("total").innerHTML = "$" + total;

        }
        
        function submitForm1() {
            var ok = true;
            
            if(document.getElementById('Description').value == "") {
                document.getElementById('descError').innerHTML = "*Please fill in the description of your order";
                ok = false;
            }
            else {
                document.getElementById('descError').innerHTML = "";
            }
            
            
            if(document.getElementById('upload').value == "") {
                document.getElementById('uploadError').innerHTML = "*Please upload a reference image for your order";
                ok = false;
            }
            else {
                document.getElementById('uploadError').innerHTML = "";
            }
            
            
            if(ok == true) {
                document.form1.submit();
            }
        }
    </script>
  <!-- /Start your project here-->
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