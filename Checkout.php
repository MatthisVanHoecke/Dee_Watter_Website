<?php
    include 'code.php';
    
    if($_SESSION["username"] == "" && !isset($_GET["actie"]) && $_GET["actie"] != "signout") {
        header("location: accountError.php");
    }

    $articleid = 0;
    $articleprice = 0;
    $detail = 0;
    $extrac = 0;
    $order = $_GET["orderid"];
    $artname = "";

    $fil = 0;
    $pric = 0;
    $descr = "";

    if(mysqli_connect_errno()) {
        trigger_error("fout bij verbinding: ".$mysqli->error);
    }
    else {
        $sql = "
        SELECT ArticleID, Description, File, ExtraCharacterAmount, PriceByOrder
        FROM tblorderlines
        WHERE OrderID LIKE ?
        ";
        
        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('i', $order);
            
            if(!$stmt->execute()) {
                echo "the execution has failed: ".$stmt->error." in query ".$sql;
            }
            else {
                $stmt->bind_result($articleid, $descr, $fil, $extrac, $pric);

                $stmt->fetch();
            }
            $stmt->close();
        }

        $sql = "
        SELECT ArticlePrice, o.Detailed, ArticleName
        FROM tblarticles a, tblorderlines o
        WHERE o.ArticleID LIKE ? AND a.ArticleID = o.ArticleID
        ";
        
        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('i', $articleid);
            
            if(!$stmt->execute()) {
                echo "the execution has failed: ".$stmt->error." in query ".$sql;
            }
            else {
                $stmt->bind_result($articleprice, $detail, $artname);

                $stmt->fetch();
            }
            $stmt->close();
        }
        else {
            echo $mysqli->error;
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
    
  <div class="row justify-content-center examp">
        <div class="col-md-8 profile" style="height: auto">
            <?php echo "<h1 style='text-align: center;'>".$artname."-shot</h1>";?>
            <div class="row justify-content-between" style="height: auto">
                <div class="col align-self-center">
                    <div class="row justify-content-center" style="height: 40%;">
                        <div class="col-md-8">
                            <img src="References/<?php echo $fil.".jpg";?>" class="img-thumbnail" >
                        </div>
                    </div>
                    <div class="row justify-content-center" style="margin: 10px; background-color: white; border-radius: 10px 10px 10px 10px; height: 40%;">
                        <div class="col-md-12 text-center">
                            <div id="scrollbar" style="height: 95%">
                                <p style="padding: 10px;" class="text-break">
                                    <?php echo $descr;?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col align-self-center" style="margin: 10px; background-color: white; border-radius: 10px 10px 10px 10px; height: auto; padding-top: 5%; padding-bottom: 5%" >
                    <div class="row justify-content-center">
                        <div class="col-md-8 justify-content-center">
                            <h3 style="padding-top: 5px;">
                                <input type="text" class="text-center" style="width: 100%" disabled value="$<?php echo $pric;?>">
                            </h3>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="margin-top: 20px; height: auto">
                        <div class="col-md-8">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="form1" name="form1" method="post" action="Index.php"></form>
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AdqSm8EhP7tJ8lmJBewcp3SHcShEnAqjZtiyxxpYeDkvtslj6KyjeBI8RRprmkMf4nncO651wpu1EWoZ&components=buttons"></script>
    <script type="text/javascript">
        var total = 0;
        
        var parts = window.location.search.substr(1).split("&");
        var $_GET = {};
        for (var i = 0; i < parts.length; i++) {
            var temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
        }

        window.onload = loadValues();
        
        var article = new Array();
        
        function loadValues() {

            paypal.Buttons({
                createOrder: function(data, actions) {
                    // Set up the transaction
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                currency_code: 'USD',
                                value: '0.01'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        $.get("updateStatus.php?orderid=" + $_GET["orderid"], function(data) {
                            if(data == "Success") {
                                alert("Order successfully queued!");
                                document.form1.submit();
                            }
                            else {
                                alert(data);
                            }
                        });
                        alert('Transaction Completed by ' + details.payer.name.given_name);
                    });
                }
            }).render('#paypal-button-container');
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