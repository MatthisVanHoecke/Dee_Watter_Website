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

<body onload="loadValues()">

  <!-- Start your project here-->
  <?php include "standard.php"; ?>
    
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
        var artid = new Array(), articlehead = new Array(), articlehalf = new Array(), articlefull = new Array(), backcolor = new Array(), rows = 0, temporder = new Array();
        var tabcount = 0, ordercount = 0;

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
                for(var i = 0; i < order.length; i++) {
                    if(order[i].includes("Process")) {
                        temporder[ordercount] = order[i];
                        ordercount++;
                    }
                }
                createTable();
            });
        }
      
      
        function createTable() {
            tabcount = 0;
            var checked = new Array();
            for(var i = 0; i < temporder.length; i++) {
                splitorder = temporder[i].split("§");

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
            for(var i = 0; i < temporder.length; i++) {
                table += "<tr style='height: 80px' id='row" + tabcount + "'><td style='width: 5%'><input type='checkbox' id='selectedrow"+ tabcount +"'></td><td style='width: 5%'>" + id[tabcount] + "</td><td style='overflow-wrap: break-word;'><div style='height: 80px; overflow-y: auto'><textarea id='area" + tabcount + "' style='width: 90%; margin-top: 5px;' rows='4' onfocusout='updateDescription(" + tabcount + ")'>" + description[tabcount] + "</textarea></div></td><td style='width: 15%; overflow-x: auto;'>" + file[tabcount] + "<input type='file' id='file" + tabcount + "' onfocusout='updateFile(" + tabcount + ")'></td><td style='width: 12%; overflow-x: auto;'><input type='checkbox' name='detailed' " + checked[tabcount] + " id='detailed" + tabcount + "' onclick='updateDetailed(" + i + ")'></td><td style='width: 12%; overflow-x: auto;'><input type='number' id='extra" + tabcount + "' min='0' max='4' value='" + extr[tabcount] + "' style='width: 60%' onchange='updateExtraCharacter(" + tabcount + ")'></td><td style='width: 7%; overflow-x: auto;' id = 'price" + tabcount + "' >" + price[tabcount] + "</td><td style='width: 15%; overflow-x: auto;'>" + artid[tabcount] + "</td><td style='width: 10%'><input type='button' value='Delete' onclick='deleteValues(" + tabcount + ")' style='width: 90%'></td></tr>";
                tabcount++;
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
            temporder.splice(num,1);
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