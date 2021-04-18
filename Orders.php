<?php
    include "code.php";

    $detail = 0;

    if($_SESSION["username"] == "" && !isset($_GET["actie"]) && $_GET["actie"] != "signout") {
        header("location: accountError.php");
    }

    if(isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["orderid"])) {
        header("location: EditUser.php?action=edit&customerid=".$_COOKIE['customerid']);
    }

    if(isset($_POST["save"])) {
        $sql = "
        UPDATE tblorderlines SET Detailed = ?, ExtraCharacter = ?, PriceByOrder = ?, Status = ?
        WHERE OrderID = ?
        ";

        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('iidsi', $detai, $extr, $pric, $stat, $customid);

            if(isset($_POST["detail"])) {
                $detai = 1;
            }
            else {
                $detai = 0;
            }

            if(isset($_POST["extra"])) {
                $extr = 1;
            }
            else {
                $extr = 0;
            }

            if(isset($_POST['price'])) {
                $pric = $mysqli->real_escape_string($_POST['price']);   
            }
            else {
                $pric = $price;
            }

            if(isset($_POST['status'])) {
                $stat = $mysqli->real_escape_string($_POST['status']);   
            }
            else {
                $stat = $status;
            }

            $customid = $_GET["customerid"];

            if(!$stmt->execute()) {
                echo "het uitvoeren is mislukt: ".$stmt->error."in query ".$sql;
            }

            $stmt->close();
        }
        else {
            echo "er zit een fout in de query: ".$mysqli->error;
        }
    }

    if(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["customerid"])) {
        
        if(mysqli_connect_errno()) {
            trigger_error("Error when connecting: ".$mysqli->error);
        }
        else {
            $sql = "
            SELECT CustomerID, Username, Email
            FROM tblCustomers
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
                    
                    setcookie("customerid", $_GET["customerid"], time() + (86400*30));
                    
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
                <h3 style="font-weight:bold;">Edit Order</h3>
                <table class="tab1" style="table-layout: fixed">
                    <tr>
                        <td style="width: 5%" id="id">
                            ID &darr;
                        </td>
                        <td id="desc">
                            Description
                        </td>
                        <td style="width: 10%;  overflow-x: auto;" id="file">
                            File
                        </td>
                        <td style="width: 12%; overflow-x: auto;" id="detail">
                            Detailed
                        </td>
                        <td style="width: 12%; overflow-x: auto;" id="extr">
                            Extra Character
                        </td>
                        <td style="width: 12%; overflow-x: auto;" id="price">
                            Price
                        </td>
                        <td style="width: 15%; overflow-x: auto;" id="stat">
                            Status
                        </td>
                        <td style="width: 10%; overflow-x: auto;" id="date">
                            Date
                        </td>
                    </tr>
                </table>
                <form name="form1" id="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']."?action=edit&customerid=".$_COOKIE['customerid'];?>">
                <div id="scrollbar">
                    <table class="tab2" id="orders" style="background-color: white; table-layout: fixed;">
                        
            
                    </table>
                </div>
                <div class="d-flex justify-content-center" style="width: 100%">
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

function submitFormDelete() {
    
    document.form1.submit();
}

var parts = window.location.search.substr(1).split("&");
var $_GET = {};
for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

window.onload = loadValues();
var order = new Array();
var filter = "";

function resetArrows() {
    if(filter != "id") { document.getElementById("id").innerHTML = "ID"; }
    if(filter != "desc") { document.getElementById("desc").innerHTML = "Description"; }
    if(filter != "file") { document.getElementById("file").innerHTML = "File"; }
    if(filter != "detail") { document.getElementById("detail").innerHTML = "Detailed"; }
    if(filter != "extr") { document.getElementById("extr").innerHTML = "Extra Character"; }
    if(filter != "price") { document.getElementById("price").innerHTML = "Price"; }
    if(filter != "stat") { document.getElementById("stat").innerHTML = "Status"; }
    if(filter != "date") { document.getElementById("date").innerHTML = "Date"; }
}

function loadValues() {
    $.get("getAllOrders.php", function(data) {
        order = data.split("æ");
        createTable();
    });

    document.getElementById("date").addEventListener("click", function() {
        if(filter == "date") {
            filter = "dateascend";
            document.getElementById("date").innerHTML = "Date &darr;";
        }
        else {
            filter = "date";
            document.getElementById("date").innerHTML = "Date &uarr;";
            resetArrows();
        }
        createTable();
    });

    document.getElementById("id").addEventListener("click", function() {
        if(filter == "id") {
            filter = "idascend";
            document.getElementById("id").innerHTML = "ID &darr;";
        }
        else {
            filter = "id";
            document.getElementById("id").innerHTML = "ID &uarr;";
            resetArrows();
        }
        createTable();
    });

    document.getElementById("detail").addEventListener("click", function() {
        if(filter == "detail") {
            filter = "detailascend";
            document.getElementById("detail").innerHTML = "Detailed &darr;";
        }
        else {
            filter = "detail";
            document.getElementById("detail").innerHTML = "Detailed &uarr;";
            resetArrows();
        }
        createTable();
    });

    document.getElementById("extr").addEventListener("click", function() {
        if(filter == "extr") {
            filter = "extrascend";
            document.getElementById("extr").innerHTML = "Extra Character &darr;";
        }
        else {
            filter = "extr";
            document.getElementById("extr").innerHTML = "Extra Character &uarr;";
            resetArrows();
        }
        createTable();
    });

    document.getElementById("price").addEventListener("click", function() {
        if(filter == "price") {
            filter = "priceascend";
            document.getElementById("price").innerHTML = "Price &darr;";
        }
        else {
            filter = "price";
            document.getElementById("price").innerHTML = "Price &uarr;";
            resetArrows();
        }
        createTable();
    });

    document.getElementById("stat").addEventListener("click", function() {
        if(filter == "stat") {
            filter = "statascend";
            document.getElementById("stat").innerHTML = "Status &darr;";
        }
        else {
            filter = "stat";
            document.getElementById("stat").innerHTML = "Status &uarr;";
            resetArrows();
        }
        createTable();
    });
}

var splitorder = new Array();
var count = 0;
var id = new Array(), description = new Array(), file = new Array(), detailed = new Array(), extr = new Array(), price = new Array(), stat = new Array(), deleteid = new Array(), queue = new Array(), progress = new Array(), done = new Array(), allids = new Array(), inprocess = new Array();
var statusnumber = new Array(), date = new Array(), table = new Array(), checked = new Array(), alldate = new Array();

function createTable() {

    if(splitorder.length == 0) {
        for(var i = 0; i < order.length-1; i++) {
            splitorder = order[i].split("§");

            id[i] = splitorder[0];
            description[i] = splitorder[1];
            file[i] = splitorder[2];
            detailed[i] = splitorder[3];
            if(splitorder[3] == "1") {
                checked[i] = "yes";
            }
            else {
                checked[i] = "no";
            }
            extr[i] = splitorder[4];
            price[i] = splitorder[5];
            stat[i] = splitorder[6];

            date[i] = splitorder[8];

                    
            setStat(i);
            alldate[i] = {id: id[i], description: description[i], file: file[i], checked: checked[i], detailed: detailed[i], extr: extr[i], price: price[i], date: date[i], inprocess: inprocess[i], progress: progress[i], done: done[i], queue: queue[i], stat: stat[i]};
        }
    }

    var endtable = "";

    switch(filter) {
        case "date":
            sortByDate(true);
            break;
        case "id":
            sortByID(true);
            break;
        case "detail":
            sortByDetailed(true);
            break;
        case "extr":
            sortByExtraChar(true);
            break;
        case "price":
            sortByPrice(true);
            break;
        case "stat":
            sortByStatus(true);
            break;
        case "dateascend":
            sortByDate(false);
            break;
        case "idascend":
            sortByID(false);
            break;
        case "detailascend":
            sortByDetailed(false);
            break;
        case "extrascend":
            sortByExtraChar(false);
            break;
        case "priceascend":
            sortByPrice(false);
            break;
        case "statascend":
            sortByStatus(false);
            break;
        default:
            break;
    }
    for(var i = 0; i < order.length-1; i++) {
        
        table[i] = "<tr style='height: 80px'><td style='width: 5%'>" + alldate[i].id + "</td><td style='width: 32%, overflow-wrap: break-word;'><div style='height: 100%; overflow-y: auto'>" + alldate[i].description + "</div></td><td style='width: 10%; overflow-x: auto;'>" + alldate[i].file + "</td><td style='width: 12%; overflow-x: auto;'><label name='detailed' id='detailed" + i + "'>"+ alldate[i].checked +"</label></td><td style='width: 12%; overflow-x: auto;'><label id='extra" + i + "' style='width: 90%'>" + alldate[i].extr + "</label></td><td style='width: 12%; overflow-x: auto;'><label id='price" + i + "' style='width: 90%'>"+ alldate[i].price +"</label></td><td style='width: 15%; overflow-x: auto;'><select id='status" + i + "' onfocusout='updateStatus(" + i + ")'><option " + alldate[i].inprocess + ">In Process</option><option " + alldate[i].queue + ">In Queue</option><option " + alldate[i].progress + ">In Progress</option><option " + alldate[i].done + ">Done</option></select></td><td style='width: 10%; overflow-x: auto;'>" + alldate[i].date + "</td></tr>";
        
        
        endtable += table[i];
    }
    document.getElementById("orders").innerHTML = endtable;
}

function setStat(i) {
    switch(stat[i]) {
        case "In Queue":
            queue[i] = "selected";
            progress[i] = "";
            done[i] = "";
            inprocess[i] = "";
            statusnumber[i] = 0;
            if(typeof alldate[i] !== "undefined") {
                alldate[i].queue = "selected";
                alldate[i].progress = "";
                alldate[i].done = "";
                alldate[i].inprocess = "";
            }
            break;
        case "In Progress":
            queue[i] = "";
            progress[i] = "selected";
            done[i] = "";
            inprocess[i] = "";
            statusnumber[i] = 1;
            if(typeof alldate[i] !== "undefined") {
                alldate[i].queue = "";
                alldate[i].progress = "selected";
                alldate[i].done = "";
                alldate[i].inprocess = "";
            }
            break;
        case "Done":
            queue[i] = "";
            progress[i] = "";
            done[i] = "selected";
            inprocess[i] = "";
            statusnumber[i] = 2;
            if(typeof alldate[i] !== "undefined") {
                alldate[i].queue = "";
                alldate[i].progress = "";
                alldate[i].done = "selected";
                alldate[i].inprocess = "";
            }
            break;
        case "In Process":
            queue[i] = "";
            progress[i] = "";
            done[i] = "";
            inprocess[i] = "selected";
            statusnumber[i] = 3;
            if(typeof alldate[i] !== "undefined") {
                alldate[i].queue = "";
                alldate[i].progress = "";
                alldate[i].done = "";
                alldate[i].inprocess = "selected";
            }
            break;
    }
}

function sortByDate(ascending) {
    if(ascending) {
        alldate.sort(function(a,b) {
            return new Date(b.date) - new Date(a.date);
        });
    }
    else {
        alldate.sort(function(a,b) {
            return new Date(a.date) - new Date(b.date);
        });
    }
}

function sortByID(ascending) {
    if(ascending) {
        alldate.sort(function(a,b) {
            return b.id - a.id;
        });
    }
    else {
        alldate.sort(function(a,b) {
            return a.id - b.id;
        });
    }
}

function sortByDetailed(ascending) {
    if(ascending) {
        alldate.sort(function(a,b) {
            return b.checked.localeCompare(a.checked);
        });
    }
    else {
        alldate.sort(function(a,b) {
            return a.checked.localeCompare(b.checked);
        });
    }
}

function sortByExtraChar(ascending) {
    if(ascending) {
        alldate.sort(function(a,b) {
            return b.extr - a.extr;
        });
    }
    else {
        alldate.sort(function(a,b) {
            return a.extr - b.extr;
        });
    }
}

function sortByPrice(ascending) {
    if(ascending) {
        alldate.sort(function(a,b) {
            return b.price - a.price;
        });
    }
    else {
        alldate.sort(function(a,b) {
            return a.price - b.price;
        });
    }
}

function sortByStatus(ascending) {
    if(ascending) {
        alldate.sort(function(a,b) {
            return b.stat.localeCompare(a.stat);
        });
    }
    else {
        alldate.sort(function(a,b) {
            return a.stat.localeCompare(b.stat);
        });
    }
}



function deleteValues(num) {
    order.splice(num,1);
    document.getElementById("bruh").innerHTML = num;
    deleteid.push(id[num]);
    createTable();
}

var data = "";
var ok = true;

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
    var endstring;

    if(count == 0) {
        loaderOff();
    }
    else {
        for(var i = 0; i < count; i++) {
            var index = alldate.findIndex(x => x.id === allids[i]);
            switch(alldate[index].stat) {
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

            endstring = alldate[index].id + "," + alldate[index].detailed + "," + alldate[index].description + "," + alldate[index].extr + "," + alldate[index].price + "," + alldate[index].file + "," + statusnumber[index];

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

function updateExtraCharacter(num) {
    extr[num] = document.getElementById('extra' + num).value;
    if(!allids.includes(id[num])) {
        allids[count] = id[num];
        count++;
    }
}

function updatePrice(num) {
    price[num] = document.getElementById('price' + num).value;
    if(!allids.includes(id[num])) {
        allids[count] = id[num];
        count++;
    }
}

function updateStatus(num) {
    stat[num] = document.getElementById('status' + num).value;
    alldate[num].stat = stat[num];
    setStat(num);
    if(!allids.includes(alldate[num].id)) {
        allids[count] = alldate[num].id;
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
    document.getElementById("loadsave").style.display = "none";  
}
</script>
</body>

</html>