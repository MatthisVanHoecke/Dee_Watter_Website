<?php
    include "code.php";

    $detail = 0;

    if($_SESSION["username"] == "" && !isset($_GET["actie"]) && $_GET["actie"] != "signout") {
        header("location: accountError.php");
    }

    if(isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["orderid"])) {
        header("location: EditUser.php?action=edit&customerid=".$_COOKIE['customerid']);
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

<body onload="loadValues()">

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
                    <ul class="navbar-nav">
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
                <h3 style="font-weight:bold;">Edit Cart</h3>
                <table class="tab1" style="background-color: white; table-layout: fixed">
                    <tr>
                        <td style="width: 5%">
                            <b>ID</b>
                        </td>
                        <td>
                            <b>Description</b>
                        </td>
                        <td style="width: 8%;  overflow-x: auto;">
                            <b>File</b>
                        </td>
                        <td style="width: 10%; overflow-x: auto;">
                            <b>Detailed</b>
                        </td>
                        <td style="width: 10%; overflow-x: auto;">
                            <b>Extra Character</b>
                        </td>
                        <td style="width: 10%; overflow-x: auto;">
                            <b>Price</b>
                        </td>
                        <td style="width: 15%; overflow-x: auto;">
                            <b>Status</b>
                        </td>
                        <td style="width: 10%">
                            <b>Delete</b>
                        </td>
                    </tr>
                </table>
                <form name="form1" id="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']."?action=edit&customerid=".$_COOKIE['customerid'];?>">
                <div id="scrollbar">
                    <table class="tab2" id="orders" style="background-color: white; table-layout: fixed">
                        
            
                    </table>
                </div>
                <h3><b id="totalprice"></b></h3>
                <div class="row justify-content-center" style="width: 100%">
                    <button type="button" name="save" id="save" class="btn btn-default" onclick="saveValues()">Save</button>
                    <button type="button" name="loadsave" id="loadsave" class="btn btn-default" style="display: none;">
                        <div class="spinner-border text-light" role="status" style="display: none; width: 1.3rem; height: 1.3rem;" id="loadOrder">
                        </div>
                    </button>
                </div>
            </form>
            <label id="bruh"></label>
        </div>
    </div>
  <!-- /Start your project here-->
    
    <script type="text/javascript">
        
        var parts = window.location.search.substr(1).split("&");
        var $_GET = {};
        for (var i = 0; i < parts.length; i++) {
            var temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
        }
        
        var order = new Array();
        var splitorder = new Array();
        var id = new Array(), description = new Array(), file = new Array(), detailed = new Array(), extr = new Array(), price = new Array(), stat = new Array(), deleteid = new Array(), queue = new Array(), progress = new Array();
        
        function createTable() {
            var checked = new Array();
            for(var i = 0; i < order.length-1; i++) {
                splitorder = order[i].split("§");

                id[i] = splitorder[0];
                description[i] = splitorder[1];
                file[i] = splitorder[2];
                detailed[i] = splitorder[3];
                if(splitorder[3] == "1") {
                    checked[i] = "checked";
                }
                else {
                    checked[i] = "";
                }
                extr[i] = splitorder[4];
                price[i] = splitorder[5];
                stat[i] = splitorder[6];
                if(stat[i] == "In Queue") {
                    queue[i] = "selected";
                    progress[i] = "";
                }
                else {
                    queue[i] = "";
                    progress[i] = "selected";
                }
            }
            
            var table = "";
            for(var i = 0; i < order.length-1; i++) {
                table += "<tr style='height: 80px'><td style='width: 5%'>" + id[i] + "</td><td style='width: 32%, overflow-wrap: break-word;'><div style='height: 100%; overflow-y: auto'><textarea id='area" + i + "' style='width: 90%' rows='4' onfocusout='updateDescription(" + i + ")'>" + description[i] + "</textarea></div></td><td style='width: 8%; overflow-x: auto;'>" + file[i] + "<input type='file' id='file" + i + "' onfocusout='updateFile(" + i + ")'></td><td style='width: 10%; overflow-x: auto;'><input type='checkbox' name='extra' " + checked[i] + " id='detailed" + i + "' onfocusout='updateDetailed(" + i + ")'></td><td style='width: 10%; overflow-x: auto;'><input type='number' id='extra" + i + "' min='0' max='5' value='" + extr[i] + "' style='width: 90%' onfocusout='updateExtraCharacter(" + i + ")'></td><td style='width: 10%; overflow-x: auto;'>" + price[i] + "</td><td style='width: 15%; overflow-x: auto;'>" + stat[i] + "</td><td style='width: 10%'><input type='button' value='Delete' onclick='deleteValues(" + i + ")' style='width: 90%'></td></tr>";
            }
            
            var totalprice = 0;
            document.getElementById("orders").innerHTML = table;
            for(var i = 0; i < price.length; i++) {
                totalprice += parseFloat(price[i]);
            }
            document.getElementById("totalprice").innerHTML = "Total: $" + totalprice;
        }
        
        function loadValues() {
            $.get("getOrders.php?customerid=" + $_GET["customerid"], function(data) {
                order = data.split("æ");
                createTable();
            });
        }
        
        function deleteValues(num) {
            order.splice(num,1);
            document.getElementById("bruh").innerHTML = num;
            deleteid.push(id[num]);
            createTable();
        }
        
        function saveValues() {
            document.getElementById("bruh").innerHTML = "";
            loaderOn();
            
            if(deleteid.length > 0) {
                for(var i = 0; i < deleteid.length; i++) {
                    $.get("deleteValues.php?id=" + deleteid[i], function(data) {
                        loaderOff();
                        document.getElementById("bruh").innerHTML = data;
                    });
                }
            }
            var statusnumber = new Array();
            for(var i = 0; i < order.length-1; i++) {
                if(stat[i] == "In Queue") {
                    statusnumber[i] = 0;
                }
                else {
                    statusnumber[i] = 1;
                }
                document.getElementById("bruh").innerHTML = description[i];
                $.get("updateValues.php?string=" + id[i] + "," + detailed[i] + "," + description[i] + "," + extr[i] + "," + price[i] + "," + file[i] + "," + statusnumber[i], function(data) {
                    loaderOff();
                    document.getElementById("bruh").innerHTML = data;
                });
            }
        }
        
        function updateDetailed(num) {
            if(document.getElementById('detailed' + num).checked) {
                detailed[num] = "1";
            }
            else {
                detailed[num] = "0";
            }
        }
        
        function updateExtraCharacter(num) {
            extr[num] = document.getElementById('extra' + num).value;
        }
        
        function updatePrice(num) {
            price[num] = document.getElementById('price' + num).value;
        }
        
        function updateStatus(num) {
            stat[num] = document.getElementById('status' + num).value;
        }
        
        function updateFile(num) {
            if(document.getElementById('file' + num).value != "") {
                file[num] = document.getElementById('file' + num).name;   
            }
        }
        
        function updateDescription(num) {
            description[num] = document.getElementById('area' + num).value;
        }
        
        function loaderOn() {
            document.getElementById("save").style.display = "none";
            document.getElementById("loadOrder").style.display = "block";
            document.getElementById("loadsave").style.display = "block";
        }
        function loaderOff() {
            document.getElementById("save").style.display = "block";
            document.getElementById("loadOrder").style.display = "none";
            document.getElementById("loadsave").style.display = "none";  
        }
    </script>

  <!-- SCRIPTS -->
    <script type="text/javascript" src="js/deescript.js"></script>
    
  <!-- JQuery -->
  <script type="text/javascript" src="js/jquery-3.4.0.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
</body>

</html>