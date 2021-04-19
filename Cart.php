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
    
    <div class="row justify-content-center examp">
        <div class="col-md-7 profile">
                <h3 style="font-weight:bold;">Edit Cart</h3>
                <table class="tab1" style="table-layout: fixed">
                    <tr>
                        <td style="width: 5%"></td>
                        <td style="width: 5%">
                            <b>ID</b>
                        </td>
                        <td>
                            <b>Description</b>
                        </td>
                        <td style="width: 15%;  overflow-x: auto;">
                            <b>File</b>
                        </td>
                        <td style="width: 12%; overflow-x: auto;">
                            <b>Detailed</b>
                        </td>
                        <td style="width: 12%; overflow-x: auto;">
                            <b>Extra Character</b>
                        </td>
                        <td style="width: 7%; overflow-x: auto;">
                            <b>Price</b>
                        </td>
                        <td style="width: 15%; overflow-x: auto;">
                            <b>Type</b>
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
                    <div class="d-flex justify-content-center align-items-center" style="height: 100%">
                        <div class="spinner-border text-light loadPage" role="status" style="display: block; width: 5rem; height: 5rem;">
                    </div>
                </div>
                </div>
                <label id="lbltotal">Total: <input type="text" id="totalprice" readonly></label>
                <div class="d-flex justify-content-center" style="width: 100%">
                    <button type="button" name="save" id="save" class="btn btn-default" onclick="saveValues()">Save</button>
                    <button type="button" name="loadsave" id="loadsave" class="btn btn-default" style="display: none;">
                        <div class="spinner-border text-light" role="status" style="display: none; width: 1.3rem; height: 1.3rem;" id="loadOrder">
                        </div>
                    </button>
                    <button type="button" name="checkout" id="checkout" class="btn btn-default">Checkout</button>
                </div>
            </form>
            <label id="bruh"></label>
        </div>
    </div>
  <!-- /Start your project here-->

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

  <script type="text/javascript">
        
        var parts = window.location.search.substr(1).split("&");
        var $_GET = {};
        for (var i = 0; i < parts.length; i++) {
            var temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
        }
        
        var order = new Array();
        var splitorder = new Array();
        var id = new Array(), description = new Array(), file = new Array(), detailed = new Array(), extr = new Array(), price = new Array(), stat = new Array(), deleteid = new Array(), queue = new Array(), progress = new Array(), done = new Array(), allids = new Array(), inprocess = new Array();
        var statusnumber = new Array();
        var artid = new Array(), articlehead = new Array(), articlehalf = new Array(), articlefull = new Array(), backcolor = new Array(), rows = 0;
        var tabcount = 0;

        function loadValues() {
            $.get("head.php?type=Head", function(data) {
                articlehead = data.split(",");
            });
            $.get("head.php?type=HalfBody", function(data) {
                articlehalf = data.split(",");
            });
            $.get("head.php?type=FullBody", function(data) {
                articlefull = data.split(",");
            });

            $.get("getOrders.php?customerid=" + $_GET["customerid"], function(data) {
                loaderOff();
                order = data.split("æ");
                createTable();
            });
        }
      
      
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
                switch(stat[i]) {
                    case "In Queue":
                        queue[i] = "selected";
                        progress[i] = "";
                        done[i] = "";
                        inprocess[i] = "";
                        statusnumber[i] = 0;
                        break;
                    case "In Progress":
                        queue[i] = "";
                        progress[i] = "selected";
                        done[i] = "";
                        inprocess[i] = "";
                        statusnumber[i] = 1;
                        break;
                    case "Done":
                        queue[i] = "";
                        progress[i] = "";
                        done[i] = "selected";
                        inprocess[i] = "";
                        statusnumber[i] = 2;
                        break;
                    case "In Process":
                        queue[i] = "";
                        progress[i] = "";
                        done[i] = "";
                        inprocess[i] = "selected";
                        statusnumber[i] = 3;
                        break;
                }
                switch(splitorder[7]) {
                    case "1":
                        artid[i] = "Head";
                        break;
                    case "2":
                        artid[i] = "HalfBody";
                        break;
                    case "3":
                        artid[i] = "FullBody";
                        break;
                }
            }
            
            var table = "";
            for(var i = 0; i < order.length-1; i++) {
                if(stat[i] == "In Process") {
                    table += "<tr style='height: 80px' id='row" + tabcount + "'><td style='width: 5%'><input type='checkbox' id='selectedrow"+ tabcount +"'></td><td style='width: 5%'>" + id[tabcount] + "</td><td style='overflow-wrap: break-word;'><div style='height: 100%; overflow-y: auto'><textarea id='area" + tabcount + "' style='width: 90%' rows='4' onfocusout='updateDescription(" + tabcount + ")'>" + description[tabcount] + "</textarea></div></td><td style='width: 15%; overflow-x: auto;'>" + file[tabcount] + "<input type='file' id='file" + tabcount + "' onfocusout='updateFile(" + tabcount + ")'></td><td style='width: 12%; overflow-x: auto;'><input type='checkbox' name='detailed' " + checked[tabcount] + " id='detailed" + tabcount + "' onclick='updateDetailed(" + i + ")'></td><td style='width: 12%; overflow-x: auto;'><input type='number' id='extra" + tabcount + "' min='0' max='4' value='" + extr[tabcount] + "' style='width: 60%' onchange='updateExtraCharacter(" + tabcount + ")'></td><td style='width: 7%; overflow-x: auto;' id = 'price" + tabcount + "' >" + price[tabcount] + "</td><td style='width: 15%; overflow-x: auto;'>" + artid[tabcount] + "</td><td style='width: 10%'><input type='button' value='Delete' onclick='deleteValues(" + tabcount + ")' style='width: 90%'></td></tr>";
                    tabcount++;
                }
            }
            document.getElementById("orders").innerHTML = table;

            for(var i = 0; i < tabcount; i++) {
                (function() {
                    var tabid = i;
                    
                    document.getElementById("selectedrow" + i).addEventListener("click", function() {
                        if(document.getElementById("selectedrow" + tabid).checked) {
                            document.getElementById("selectedrow" + tabid).checked = false;
                            document.getElementById("row" + tabid).style.backgroundColor = backcolor[i];
                        }
                        else {
                            document.getElementById("selectedrow" + rows).checked = false;
                            document.getElementById("row" + rows).style.backgroundColor = backcolor[i];

                            document.getElementById("selectedrow" + tabid).checked = true;
                            backcolor[i] = document.getElementById("row" + tabid).style.backgroundColor;
                            document.getElementById("row" + tabid).style.backgroundColor = "#fdff82";
                            rows = tabid;
                        }
                    });
                    document.getElementById("row" + i).addEventListener("click", function() {
                        if(document.getElementById("selectedrow" + tabid).checked) {
                            document.getElementById("selectedrow" + tabid).checked = false;
                            document.getElementById("row" + tabid).style.backgroundColor = backcolor[i];
                        }
                        else {
                            document.getElementById("selectedrow" + rows).checked = false;
                            document.getElementById("row" + rows).style.backgroundColor = backcolor[i];

                            document.getElementById("selectedrow" + tabid).checked = true;
                            backcolor[i] = document.getElementById("row" + tabid).style.backgroundColor;
                            document.getElementById("row" + tabid).style.backgroundColor = "#fdff82";
                            rows = tabid;
                        }
                        
                    });
                })();
            }

            var totalprice = 0;
            for(var i = 0; i < price.length; i++) {
                if(stat[i] == "In Process") {
                    totalprice += parseFloat(price[i]);
                }
            }
            document.getElementById("totalprice").value = "$" + totalprice;

            for(var icounter = 0; icounter < tabcount; icounter++) {
                (function() {
                    var num = icounter;

                    document.getElementById("detailed" + num).addEventListener("click", function() {
                        setTimeout(function(number) {
                            onPriceChange(number);
                        }, 1, num);
                    });

                    document.getElementById("extra" + num).addEventListener("change", function() {
                        setTimeout(function(number) {
                            onPriceChange(number);
                        }, 1, num);
                    });
                })();
            }
        }
        
        function deleteValues(num) {
            order.splice(num,1);
            deleteid.push(id[num]);
            if(!allids.includes(id[num])) {
                allids[count] = id[num];
                count++;
            } 
            clearArrays();
            createTable();
        }

        function clearArrays() {
            id = [];
            description = [];
            file = [];
            detailed = [];
            checked = [];
            extr = [];
            price = [];
            stat = [];
            queue = [];
            progress = [];
            done = [];
            statusnumber = [];
            artid = [];
        }
        
        var ok = true;
        var count = 0;

        function saveValues() {
            document.getElementById("bruh").innerHTML = "";
            loaderOn();
            
            if(deleteid.length > 0) {
                for(var i = 0; i < deleteid.length; i++) {
                    $.get("deleteValues.php?id=" + deleteid[i], function(data) {
                        loaderOff();
                        if(data != "Success") {
                            ok = false;
                        }
                    });
                }
            }

            if(count == 0) {
                loaderOff();
            }
            else {
                for(var i = 0; i < count; i++) {
                    var index = id.indexOf(allids[i]);
                    switch(stat[index]) {
                        case "In Queue":
                            statusnumber[index] = 0;
                            break;
                        case "In Progress":
                            statusnumber[index] = 1;
                            break;
                        case "Done":
                            statusnumber[index] = 2;
                            break;
                        case "In Process":
                            statusnumber[index] = 3;
                            break;
                        default:
                            statusnumber[index] = 0;
                            break;
                    }

                    endstring = id[index] + "," + detailed[index] + "," + description[index] + "," + extr[index] + "," + price[index] + "," + file[index] + "," + statusnumber[index];
                    
                    $.get("updateValues.php?string=" + endstring, function(data) {
                        if(data != "Success") {
                            ok = false;
                        }
                    });
                }
            }
        }

        $(document).ajaxStop(function () {
            loaderOff();
            if(ok = true && count != 0) {
                document.getElementById("bruh").innerHTML = "Changes saved!";
                allids = [];
                count = 0;
            }
        });
        
        function updateFile(num) {
            if(document.getElementById('file' + num).value != "") {
                file[num] = document.getElementById('file' + num).name;  
                if(!allids.includes(id[num])) {
                    allids[count] = id[num];
                    count++;
                } 
            }
        }
        
        function updateDescription(num) {
            description[num] = document.getElementById('area' + num).value;
            if(!allids.includes(id[num])) {
                allids[count] = id[num];
                count++;
            }
        }

        function updateDetailed(num) {
            if(document.getElementById('detailed' + num).checked) {
                detailed[num] = "1";
            }
            else {
                detailed[num] = "0";
            }
            if(!allids.includes(id[num])) {
                allids[count] = id[num];
                count++;
            }
        }

        function onPriceChange(num) {
            switch(artid[num]) {
                case "Head":
                    price[num] = parseFloat(articlehead[0]);
                    if(detailed[num] == "1") {
                        price[num] += parseFloat(articlehead[1]);
                    }
                    price[num] += parseInt(extr[num])*parseFloat(articlehead[2]);
                    break;
                case "HalfBody":
                    price[num] = parseFloat(articlehalf[0]);
                    if(detailed[num] == "1") {
                        price[num] += parseFloat(articlehalf[1]);
                    }
                    price[num] += parseInt(extr[num])*parseFloat(articlehalf[2]);
                    break;
                case "FullBody":
                    price[num] = parseFloat(articlefull[0]);
                    if(detailed[num] == "1") {
                        price[num] += parseFloat(articlefull[1]);
                    }
                    price[num] += parseInt(extr[num])*parseFloat(articlefull[2]);
                    break;
            }
            document.getElementById("price" + num).innerHTML = parseFloat(price[num]);

            var totalprice = 0;
            for(var i = 0; i < price.length; i++) {
                if(stat[i] == "In Process") {
                    totalprice += parseFloat(price[i]);
                }
            }
            document.getElementById("totalprice").value = "$" + totalprice;
        }

        function updateExtraCharacter(num) {
            extr[num] = document.getElementById('extra' + num).value;
            if(!allids.includes(id[num])) {
                allids[count] = id[num];
                count++;
            }
        }
        
        function loaderOn() {
            document.getElementById("save").style.display = "none";
            document.getElementById("loadOrder").style.display = "block";
            document.getElementById("loadsave").style.display = "block";
        }
        function loaderOff() {
            document.getElementById("save").style.display = "block";
            document.getElementById("loadOrder").style.display = "none";
            document.getElementsByClassName("loadPage")[0].style.display = "none";
            document.getElementsByClassName("tab2")[0].style.height = "40%";
            document.getElementById("loadsave").style.display = "none";  
        }
    </script>
</body>

</html>