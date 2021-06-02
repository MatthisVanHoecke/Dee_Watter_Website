<?php
    include "code.php";

    $detail = 0;

    if($_SESSION["username"] == "" && !isset($_GET["actie"]) && $_GET["actie"] != "signout") {
        header("location: accountError.php");
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

<body>

  <!-- Start your project here-->
  <?php include "standard.php"; ?>

  <div class="modal fade" id="modalBuyError" tabindex="-1" role="dialog" aria-labelledby="modalBuyError" aria-hidden="true">
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
        <label>Please select the item you want to buy.</label>
      </div>
    </div>
    </div>
    </div>
    
    <div class="row justify-content-center examp">
        <div class="col-md-7 profile">
                <h3 style="font-weight:bold;">Previous/Ongoing Orders</h3>
                <table class="tab1" style="table-layout: fixed">
                    <tr>
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
                        <td style="width: 12%; overflow-x: auto;">
                            <b>Status</b>
                        </td>
                    </tr>
                </table>
                <form name="form1" id="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']."?action=edit&customerid=".$_COOKIE['customerid'];?>">
                <div id="scrollbar" style="height: 500px;">
                    <table class="tab2" id="orders" style="background-color: white; table-layout: fixed">
                        
            
                    </table>
                    <div class="d-flex justify-content-center align-items-center" id='spin' style='height: 100%'>
                        <div class="spinner-border text-light loadPage" role="status" style="display: block; width: 5rem; height: 5rem;">
                    </div>
                </div>
                </div>
                <label id="lbltotal">Total: <input type="text" id="totalprice" readonly></label>
            </form>
            <form name="form2" id="form2" method="post" action="" style="display: none">

            </form>
            <label id="issues"></label>
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
        var tabcount = 0, ordercount = 0, articletypes = new Array();

        window.onload = loadValues();

        function loadValues() {
            $.get("head.php", function(data) {
                articletypes = data.split("ç");

                articlehead = articletypes[0].split(",");
                articlehalf = articletypes[1].split(",");
                articlefull = articletypes[2].split(",");
            });

            $.get("getMyOrders.php?customerid=" + $_GET["customerid"], function(data) {
                loaderOff();
                order = data.split("æ");
                for(var i = 0; i < order.length-1; i++) {
                    if(!order[i].includes("Process")) {
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
                    checked[i] = "yes";
                }
                else {
                    checked[i] = "no";
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
                table += "<tr id='row" + tabcount + "' style='height: 100px;'><td style='width: 5%'>" + id[tabcount] + "</td><td style='overflow-wrap: break-word;'><div>" + description[tabcount] + "</div></td><td style='width: 15%; overflow-x: auto;'><img src='References/" + id[tabcount] + ".jpg' alt='" + id[tabcount] + "' class='img-thumbnail' name='f" + id[tabcount] + "'></td><td style='width: 12%; overflow-x: auto;'>"+ checked[tabcount] +"</td><td style='width: 12%; overflow-x: auto;'>" + extr[tabcount] + "</td><td style='width: 7%; overflow-x: auto;' id = 'price" + tabcount + "' >" + price[tabcount] + "</td><td style='width: 15%; overflow-x: auto;'>" + artid[tabcount] + "</td><td style='width: 12%'>" + stat[tabcount] + "</td></tr>";
                tabcount++;
            }
            document.getElementById("orders").innerHTML = table;

            var totalprice = 0;
            for(var i = 0; i < price.length; i++) {
                if(stat[i] != "In Process") {
                    totalprice += parseFloat(price[i]);
                }
            }
            document.getElementById("totalprice").value = "$" + totalprice;
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
            document.getElementById("issues").innerHTML = "";
            
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
                    
                    $.ajax({
                        url: "updateValues.php?string=" + endstring,
                        type: 'post',
                        data: file[index],
                        contentType: false,
                        processData: false,
                        success: function(response){
                            if(response != "Success"){
                                ok = false;
                                document.getElementById("issues").innerHTML = response;
                                alert(response);
                            }
                        }
                    });
                }
            }
        }

        function checkOut() {
            var ok = false;
            for(var i = 0; i < tabcount; i++) {
                if(document.getElementById("selectedrow" + i).checked) {
                    ok = true;
                }
            }

            if(ok == false) {
                $('#modalBuyError').modal('show');
            }
            else {
                document.form2.action = "Checkout.php?orderid=" + id[rows];
                document.form2.submit(); 
            }
        }

        $(document).ajaxStop(function () {
            loaderOff();
            if(ok = true && count != 0) {
                notify("Changes Saved!");
                allids = [];
                count = 0;
            }
        });
        
        function loaderOff() {
            document.getElementsByClassName("loadPage")[0].style.display = "none";
            document.getElementById("spin").style.height = "0px";
            document.getElementsByClassName("tab2")[0].style.height = "40%";
        }
    </script>
</body>

</html>