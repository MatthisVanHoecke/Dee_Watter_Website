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
            FROM tblcustomers
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
        UPDATE tblcustomers
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
  <?php include "standard.php"; ?>
    
    <div class="row justify-content-center">
        <div class="col-md-7 profile">
            <?php echo "<h1 style='text-align: center;'>".$id."</h1>";?>
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
                <table class="tab1" style="table-layout: fixed">
                    <tr>
                        <td style="width: 5%">
                            <b>ID</b>
                        </td>
                        <td>
                            <b>Description</b>
                        </td>
                        <td style="width: 10%;  overflow-x: auto;">
                            <b>File</b>
                        </td>
                        <td style="width: 12%; overflow-x: auto;">
                            <b>Detailed</b>
                        </td>
                        <td style="width: 12%; overflow-x: auto;">
                            <b>Extra Character</b>
                        </td>
                        <td style="width: 12%; overflow-x: auto;">
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
    document.getElementById("form1").action = "<?php echo $_SERVER['PHP_SELF']."?action=delete&orderid=".$id;?>";
    
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
function loadValues() {
    $.get("getOrders.php?customerid=" + $_GET["customerid"], function(data) {
        order = data.split("æ");
        createTable();
    });
}

var splitorder = new Array();
var count = 0;
var id = new Array(), description = new Array(), file = new Array(), detailed = new Array(), extr = new Array(), price = new Array(), stat = new Array(), deleteid = new Array(), queue = new Array(), progress = new Array(), done = new Array(), allids = new Array(), inprocess = new Array();
var statusnumber = new Array();

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
    }
    var table = "";
    for(var i = 0; i < order.length-1; i++) {
        table += "<tr style='height: 80px'><td style='width: 5%'>" + id[i] + "</td><td style='width: 32%, overflow-wrap: break-word;'><div style='height: 100%; overflow-y: auto'>" + description[i] + "</div></td><td style='width: 10%; overflow-x: auto;'>" + file[i] + "</td><td style='width: 12%; overflow-x: auto;'><input type='checkbox' name='extra' " + checked[i] + " id='detailed" + i + "' onfocusout='updateDetailed(" + i + ")'></td><td style='width: 12%; overflow-x: auto;'><input type='number' id='extra" + i + "' min='0' max='5' value='" + extr[i] + "' style='width: 90%' onfocusout='updateExtraCharacter(" + i + ")'></td><td style='width: 12%; overflow-x: auto;'><input type='text' id='price" + i + "' value='" + price[i] + "' style='width: 90%' onfocusout='updatePrice(" + i + ")'></td><td style='width: 15%; overflow-x: auto;'><select id='status" + i + "' onfocusout='updateStatus(" + i + ")'><option " + inprocess[i] + ">In Process</option><option " + queue[i] + ">In Queue</option><option " + progress[i] + ">In Progress</option><option " + done[i] + ">Done</option></select></td><td style='width: 10%'><input type='button' value='Delete' onclick='deleteValues(" + i + ")' style='width: 90%'></td></tr>";
    }
    document.getElementById("orders").innerHTML = table;
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
    document.getElementById("loadsave").style.display = "none";  
}
</script>
</body>

</html>