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
  <div class="modal fade" id="modalEmail" tabindex="-1" role="dialog" aria-labelledby="modalEmail" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
          <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
        </svg>
        <h5 class="modal-title" id="exampleModalLongTitle">Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label id="Emailtext"></label>
      </div>
    </div>
  </div>
</div>
    <div class="row justify-content-center examp" style="height: 100%">
        <div class="col-md-9 profile" style="height: 80%">
                <h3 style="font-weight:bold;">Edit Order</h3>
                <label>Search user: <input type='text' id="searchUser" onkeydown="searchUser();"></label><br>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1">
                    <label class="custom-control-label" for="customSwitch1">Toggle filter</label>
                </div>
                <div id="filter" style="display: none;">
                    <div class="row filteritems">
                        <div class="col">
                            <input type="checkbox" id="detailFilter" onchange="detailFilter();">
                            <label for="detailFilter">Detailed</label>
                        </div>
                    </div>
                    <div class="row filteritems">
                        <div class="col">
                            <label>Extra Characters: <input type="number" style='width: 50px;' id="extraCharMin" min="0" max="4" value="0" onchange="extraCharacterRange()"> to <input type="number" style='width: 50px;' id="extraCharMax" min="0" max="4" value="4" onchange="extraCharacterRange()"></label>
                        </div>
                    </div>
                    <div class="row filteritems">
                        <div class="col">
                            <label>Price Range: <input type="number" style='width: 50px;' id="priceMin" min="0" value="0" onchange="priceRange()"> to <input type="number" style='width: 50px;' id="priceMax" value="100" onfocusout="priceRange()"></label>
                        </div>
                    </div>
                    <div class="row filteritems">
                        <div class="col">
                            <label>Status: 
                                <select id='statusFilter' onchange="statusFilter()">
                                    <option value='uhoh'></option>
                                    <option value='In Queue'>In Queue</option>
                                    <option value='In Progress'>In Progress</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="row filteritems">
                        <div class="col">
                            <label>Date Range: <input type="date" id='dateMin' onchange="dateRange()"> to <input type="date" id='dateMax' onchange="dateRange()" ></label>
                        </div>
                    </div>
                </div>
                    <table class="tab1" style="table-layout: fixed">
                        <tr>
                            <td style="width: 5%" id="id">
                                ID &darr;
                            </td>
                            <td style="width: 12%" id="sortusername">
                                User
                            </td>
                            <td id="desc">
                                Description
                            </td>
                            <td style="width: 10%;  overflow-x: auto;" id="file">
                                File
                            </td>
                            <td style="width: 8%; overflow-x: auto;" id="detail">
                                Detailed
                            </td>
                            <td style="width: 12%; overflow-x: auto;" id="extr">
                                Extra Character
                            </td>
                            <td style="width: 12%; overflow-x: auto;" id="price">
                                Price
                            </td>
                            <td style="width: 10%; overflow-x: auto;" id="stat">
                                Status
                            </td>
                            <td style="width: 10%; overflow-x: auto;" id="date">
                                Date
                            </td>
                        </tr>
                    </table>
                <form name="form1" id="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']."?action=edit&customerid=".$_COOKIE['customerid'];?>">
                <div id="scrollbar" style="height: 500px">
                    <table class="tab2" id="orders" style="background-color: white; table-layout: fixed;">
                        
            
                    </table>
                    <div class="d-flex justify-content-center align-items-center" id='spin' style="height: 100%">
                        <div class="spinner-border text-light loadPage" role="status" style="display: block; width: 5rem; height: 5rem;">
                    </div>
                </div>
                </div>
                <div class="d-flex justify-content-center" style="width: 100%">
                    <button type="button" name="save" id="save" class="btn btn-default" onclick="saveValues()">Save</button>
                    <button type="button" name="loadsave" id="loadsave" class="btn btn-default" style="display: none;">
                        <div class="spinner-border text-light" role="status" style="display: none; width: 1.3rem; height: 1.3rem;" id="loadOrder">
                        </div>
                    </button>
                </div>
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
var order = new Array(), temporder = new Array();
var range = new Array();
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
    if(filter != "sortusername") { document.getElementById("sortusername").innerHTML = "User"; }
}

function loadValues() {
    $.get("getAllOrders.php", function(data) {
        order = data.split("æ");
        temporder = order.slice();
        createTable();
    });

    document.getElementById("customSwitch1").addEventListener("click", function() {
        if(document.getElementById("customSwitch1").checked) {
            document.getElementById("filter").style.display = "block";
        }
        else {
            document.getElementById("filter").style.display = "none";
            order = temporder.slice();
            searchUser();
        }
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

    document.getElementById("sortusername").addEventListener("click", function() {
        if(filter == "sortusername") {
            filter = "sortusernameascend";
            document.getElementById("sortusername").innerHTML = "User &darr;";
        }
        else {
            filter = "sortusername";
            document.getElementById("sortusername").innerHTML = "User &uarr;";
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
var statusnumber = new Array(), date = new Array(), table = new Array(), checked = new Array(), alldate = new Array(), customer = new Array(), email = new Array();
var temparr = new Array();

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
            
            customer[i] = splitorder[9];

            email[i] = splitorder[10];

                    
            setStat(i);
            alldate[i] = {id: id[i], description: description[i], file: file[i], checked: checked[i], detailed: detailed[i], extr: extr[i], price: price[i], date: date[i], inprocess: inprocess[i], progress: progress[i], done: done[i], queue: queue[i], stat: stat[i], user: customer[i], email: email[i]};
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
        case "sortusername":
            sortByUser(true);
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
        case "sortusernameascend":
            sortByUser(false);
            break;
        default:
            break;
    }
    for(var i = 0; i < order.length-1; i++) {
        
        table[i] = "<tr style='height: 100px' id='row" + i + "'><td style='width: 5%'>" + alldate[i].id + "</td><td style='width: 12%'><a style='color: #3366BB' onclick='showModal(" + i + ")' data-toggle='tooltip' data-placement='top' title='" + alldate[i].email + "'>" + alldate[i].user + "</a></td><td style='overflow-wrap: break-word;'><div>" + alldate[i].description + "</div></td><td style='width: 10%'><img src='References/" + alldate[i].file + ".jpg' alt='" + alldate[i].file + "' class='img-thumbnail' /></td><td style='width: 8%; overflow-x: auto;'><label name='detailed' id='detailed" + i + "'>"+ alldate[i].checked +"</label></td><td style='width: 12%; overflow-x: auto;'><label id='extra" + i + "' style='width: 90%'>" + alldate[i].extr + "</label></td><td style='width: 12%; overflow-x: auto;'><label id='price" + i + "' style='width: 90%'>"+ alldate[i].price +"</label></td><td style='width: 10%; overflow-x: auto;'><select id='status" + i + "' onfocusout='updateStatus(" + i + ")'><option " + alldate[i].queue + ">In Queue</option><option " + alldate[i].progress + ">In Progress</option><option " + alldate[i].done + ">Done</option></select></td><td style='width: 10%; overflow-x: auto;'>" + alldate[i].date + "</td></tr>";
        
        
        endtable += table[i];
    }
    document.getElementById("orders").innerHTML = endtable;

    $('[data-toggle="tooltip"]').tooltip();
}

function showModal(i) {
    document.getElementById("Emailtext").innerHTML = alldate[i].email;
    $('#modalEmail').modal('show');
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

function sortByUser(ascending) {
    if(ascending) {
        alldate.sort(function(a,b) {
            return b.user.localeCompare(a.user);
        });
    }
    else {
        alldate.sort(function(a,b) {
            return a.user.localeCompare(b.user);
        });
    }
}


var data = "";
var ok = true;

function saveValues() {
    document.getElementById("issues").innerHTML = "";
    loaderOn();

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
        notify("Changes Saved!");
        allids = [];
        count = 0;
    }
    document.getElementsByClassName("examp")[0].style.height = "auto";
    createTable();
});

function updateStatus(num) {
    stat[num] = document.getElementById('status' + num).value;
    alldate[num].stat = stat[num];
    setStat(num);
    if(!allids.includes(alldate[num].id)) {
        allids[count] = alldate[num].id;
        count++;
    }
}

function searchUser() {
    for(var i = order.length-2; i >= 0; i--) {
        document.getElementById("row" + i).style.display = "table-row";
    }
    setTimeout(() => {
        if(document.getElementById("searchUser").value != "" && document.getElementById("searchUser").value != null) {
            for(var i = order.length-2; i >= 0; i--) {
                if(!alldate[i].user.toLowerCase().startsWith(document.getElementById("searchUser").value.toLowerCase())) {
                    document.getElementById("row" + i).style.display = "none";
                }
            }
        }
        else {
            for(var i = order.length-2; i >= 0; i--) {
                document.getElementById("row" + i).style.display = "table-row";
            }
            checkFilters("search");
        }
    }, 1);
}

function detailFilter() {
    setTimeout(() => {
        if(document.getElementById("detailFilter").checked && document.getElementById("customSwitch1").checked) {
            for(var i = order.length-2; i >= 0; i--) {
                if(alldate[i].detailed == "0") {
                    document.getElementById("row" + i).style.display = "none";
                }
            }
        }
        else {
            for(var i = order.length-2; i >= 0; i--) {
                document.getElementById("row" + i).style.display = "table-row";
            }
            checkFilters("detail");
        }
    }, 1);
}

function extraCharacterRange() {
    for(var i = order.length-2; i >= 0; i--) {
        document.getElementById("row" + i).style.display = "table-row";
    }
    setTimeout(() => {
        if(document.getElementById("customSwitch1").checked) {
            for(var i = order.length-2; i >= 0; i--) {
                if(alldate[i].extr < document.getElementById("extraCharMin").value || alldate[i].extr > document.getElementById("extraCharMax").value) {
                    document.getElementById("row" + i).style.display = "none";
                }   
            }
        }
        else {
            for(var i = order.length-2; i >= 0; i--) {
                document.getElementById("row" + i).style.display = "table-row";
            }
            checkFilters("extra");
        }
    }, 1);

}

function priceRange() {
    for(var i = order.length-2; i >= 0; i--) {
        document.getElementById("row" + i).style.display = "table-row";
    }
    setTimeout(() => {
        if(document.getElementById("customSwitch1").checked) {
            for(var i = order.length-2; i >= 0; i--) {
                if(alldate[i].price < parseInt(document.getElementById("priceMin").value) || alldate[i].price > parseInt(document.getElementById("priceMax").value)) {
                    document.getElementById("row" + i).style.display = "none";
                }   
            }
        }
        else {
            for(var i = order.length-2; i >= 0; i--) {
                document.getElementById("row" + i).style.display = "table-row";
            }
            checkFilters("extra");
        }
    }, 1);
}

function dateRange() {
    for(var i = order.length-2; i >= 0; i--) {
        document.getElementById("row" + i).style.display = "table-row";
    }
    setTimeout(() => {
        if(document.getElementById("customSwitch1").checked) {
            for(var i = order.length-2; i >= 0; i--) {
                if(new Date(alldate[i].date) < new Date(document.getElementById("dateMin").value) || new Date(alldate[i].date) > new Date(document.getElementById("dateMax").value)) {
                    document.getElementById("row" + i).style.display = "none";
                }   
            }
        }
        else {
            for(var i = order.length-2; i >= 0; i--) {
                document.getElementById("row" + i).style.display = "table-row";
            }
            checkFilters("extra");
        }
    }, 1);
}

function statusFilter() {

    setTimeout(() => {
        if(document.getElementById("statusFilter").value != "" && document.getElementById("customSwitch1").checked) {
            if(document.getElementById("statusFilter").options[document.getElementById("statusFilter").selectedIndex].value == "In Queue") {
                for(var i = order.length-2; i >= 0; i--) {
                    if(alldate[i].queue != "selected") {
                        document.getElementById("row" + i).style.display = "none";
                    }
                }
            }
            if(document.getElementById("statusFilter").options[document.getElementById("statusFilter").selectedIndex].value == "In Progress") {
                for(var i = order.length-2; i >= 0; i--) {
                    if(alldate[i].progress != "selected") {
                        document.getElementById("row" + i).style.display = "none";
                    }
                }
            }
            if(document.getElementById("statusFilter").options[document.getElementById("statusFilter").selectedIndex].value == "uhoh") {
                for(var i = order.length-2; i >= 0; i--) {
                    document.getElementById("row" + i).style.display = "table-row";
                }
            }
        }
        else {
            for(var i = order.length-2; i >= 0; i--) {
                document.getElementById("row" + i).style.display = "table-row";
            }
            checkFilters('status');
        }
    }, 1);
}

function checkFilters(filt) {

    switch(filt) {
        case "search":
            if(document.getElementById("detailFilter").checked) {
                detailFilter();
                statusFilter();
            }
            break;
        case "detail":
            if(document.getElementById("searchUser").value != "") {
                searchUser();
                statusFilter();
            }
            break;
        case "extra":
            detailFilter();
            searchUser();
            statusFilter();
            break;
        case "status":
            detailFilter();
            searchUser();
            break;
        case "":
            detailFilter();
            searchUser();
            statusFilter();
            extraCharacterRange();
            break;
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
    document.getElementsByClassName("loadPage")[0].style.display = "none";
    document.getElementById("spin").style.height = "0px";
}


</script>
</body>

</html>