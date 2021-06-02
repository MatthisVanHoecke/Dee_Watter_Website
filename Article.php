<?php
    include 'code.php';
    
    if($_SESSION["username"] == "" && !isset($_GET["actie"]) && $_GET["actie"] != "signout") {
        header("location: accountError.php");
    }

    if(isset($_POST["Description"]) && $_POST["Description"] != "") {
        header("location: Index.php?uploaded=true");
    }

    $articleid = "";
    $articleprice = 0;
    $detail = 0;
    $extrac = 0;
    $customerid = 0;
    $order = 0;

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
        FROM tblcustomers
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
            SELECT OrderID
            FROM tblorders
            ORDER BY OrderID DESC LIMIT 1
            ";

            if($stmt = $mysqli->prepare($sql)) {

                if(!$stmt->execute()) {
                    echo "the execution has failed: ".$stmt->error." in query ".$sql;
                }
                else {
                    $stmt->bind_result($ordera);

                    while($stmt->fetch()) {
                        $order = $ordera;
                    }
                }
                $stmt->close();
            }
            else {
                echo "failed";
            }
            
            $sql = "
            INSERT INTO tblorders(OrderID, CustomerID, Date)
            VALUES(?,?,curdate())
            ";
            if($stmt = $mysqli->prepare($sql)) {

                $stmt->bind_param('ii', $orders, $customer);
                
                $orders = $order+1;
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
            INSERT INTO tblorderlines(OrderID, ArticleID, Description, File, Detailed, ExtraCharacter, ExtraCharacterAmount, PriceByOrder, Status)
            VALUES(?,?,?,?,?,?,?,?,?)
            ";
            
            if($stmt = $mysqli->prepare($sql)) {
                
                $stmt->bind_param('iissiiids', $orderid, $article, $desc, $file, $detailed, $extra, $extraa, $price, $status);
                
                $article = $articleid;
                
                $desc = $mysqli->real_escape_string($_POST["Description"]);
                
                $price = $articleprice;
                
                $detailed = 0;
                $extra = 0;
                $status = "In Process";
                $extraa = 0;
                
                $orderid = $order+1;

                $file = $orderid;
                
                if(isset($_POST["detail"])) {
                    $detailed = $mysqli->real_escape_string($_POST["detail"]);
                    $price += $detail;
                }
                
                
                if(isset($_POST["extra"])) {
                    $extra = $mysqli->real_escape_string($_POST["extra"]);
                    $extraa = $mysqli->real_escape_string($_POST["extranumber"]);
                    $price += $extrac*$mysqli->real_escape_string($_POST["extranumber"]);
                }
                
                if(!$stmt->execute()) {
                    echo "het uitvoeren is mislukt: ".$stmt->error." in query ".$sql;
                }
                else {
                    if (($_FILES['my_file']['name']!="")){
                        // Where the file is going to be stored
                         $target_dir = getcwd()."/References/";
                         $file = $_FILES['my_file']['name'];
                         $path = pathinfo($file);
                         $filename = $orderid;
                         $ext = "jpg";
                         $temp_name = $_FILES['my_file']['tmp_name'];
                         $path_filename_ext = $target_dir.$filename.".".$ext;
                        
                        // Check if file already exists
                        if (file_exists($path_filename_ext)) {
                         echo "Sorry, file already exists.";
                         }else{
                         move_uploaded_file($temp_name,"$target_dir/$filename".".".$ext);
                         }
                    }
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
    
  <div class="row justify-content-center">
        <div class="col-md-5 profile">
            <?php echo "<h1 style='text-align: center;'>".$_GET["article"]."-shot</h1>";?>
            <form name="form1" id="form1" class="margin" method="post" action="<?php echo $_SERVER['PHP_SELF']."?article=".$_GET['article'];?>" enctype="multipart/form-data">
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
                                <li>
                                    <input type="number" name="extranumber" id="extranumber" onclick="calculate()" value="1" style="width: 50px;" max="5" min="1">
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style="font-size: 20px; font-weight: bold;">Reference:</label>
                            <input type="file" class="form-control-file" id="upload" name="my_file">
                            <span id="uploadError" style="color: red; font-weight: bold;"></span>
                        </td>
                    </tr>
                </table>
            </form>
            <label style="font-weight:bold; font-size: 20px;" class="margin">Total: </label><input type="text" style='font-weight: bold; width: 20%' id='total'>
            <div class="row justify-content-center" style="width: 100%">
                <button type="button" name="save" id="save" class="btn btn-default" onclick="submitForm1()">Save</button>
            </div>
        </div>
    </div>
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script type="text/javascript">
        var total = 0;
        
        document.getElementById("extranumber").style.display = "none";
        var parts = window.location.search.substr(1).split("&");
        var $_GET = {};
        for (var i = 0; i < parts.length; i++) {
            var temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
        }

        window.onload = loadValues();

        $(document).ajaxStop(function () {
            $("#extra").removeAttr("disabled");
            $("#detail").removeAttr("disabled");
        });
        
        var article = new Array();
        
        function loadValues() {
            $("#extra").attr("disabled", true);
            $("#detail").attr("disabled", true);
            $.get("head.php?type=" + $_GET["article"], function(data) {
                article = data.split(",");

                total = parseFloat(article[0]);
                document.getElementById("total").value = "$" + total;
            });
        }
        
        function calculate() {
            total = parseFloat(article[0]);
            
            if(document.getElementById("detail").checked == true) {
                total += parseFloat(article[1]);
            }

            if(document.getElementById("extra").checked == true) {
                document.getElementById("extranumber").style.display = "block";
                total += parseFloat(article[2])*parseInt(document.getElementById("extranumber").value); 
            }
            else {
                document.getElementById("extranumber").style.display = "none";
            }

            document.getElementById("total").value = "$" + total;
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
                if(!document.getElementById('upload').value.toLowerCase().endsWith(".png") && !document.getElementById('upload').value.toLowerCase().endsWith(".jpeg") && !document.getElementById('upload').value.toLowerCase().endsWith(".jpg")) {
                    document.getElementById('uploadError').innerHTML = "*Please upload an image in PNG or JPG format";
                    ok = false;
                }
                else {
                    document.getElementById('uploadError').innerHTML = "";
                }
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