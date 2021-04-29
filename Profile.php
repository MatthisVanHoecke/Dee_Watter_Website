<!DOCTYPE html>

<?php

    include 'code.php';

    if($_SESSION["username"] == "" && !isset($_GET["actie"]) && $_GET["actie"] != "signout") {
        header("location: accountError.php");
    }

    if(mysqli_connect_errno()) {
        trigger_error("fout bij verbinding: ".$mysqli->error);
    }
    else {
        
        $sql = "SELECT Isadmin FROM tblCustomers WHERE Username = ?";

        if($stmt = $mysqli->prepare($sql)) {
            
            $stmt->bind_param('s', $username);
            
            $username = $_SESSION["username"];
            
            if(!$stmt->execute()) {
                echo "het uitvoeren van de query is mislukt: ".$stmt->error." in query ".$sql;
            }
            else {
            
                $stmt->bind_result($admin);
                $stmt->fetch();

                if($admin == 0) {
                    echo '<style>#adminpage {display:none;}</style>';
                }
                
                $stmt->close();
            }
        }
        else {
            echo "error";
        }
    }
    

    if(isset($_POST["change_name"]) && isset($_POST["changeUsername"]) && $_POST["changeUsername"] != "") {
        
        if(mysqli_connect_errno()) {
            trigger_error("fout bij verbinding: ".$mysqli->error);
        }
        else {
            
            $sql = "SELECT Username FROM tblCustomers WHERE Username = ?";
            
            $count = 0;
            if($stmt = $mysqli->prepare($sql)) {
                
                $stmt->bind_param('s', $username);
                
                $username = $_POST["changeUsername"];
                
                if(!$stmt->execute()) {
                    echo "het uitvoeren van de query is mislukt: ".$stmt->error." in query ".$sql;
                }
                else {

                    while($stmt->fetch()) {
                        $count++;
                    }
                }
            }
            if($count == 0) {
                $sql = "
                UPDATE tblCustomers
                SET Username = ?
                WHERE Username = ?"
                ;

                if($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param('ss', $changename, $name);

                    $changename = $mysqli->real_escape_string($_POST["changeUsername"]);
                    
                    $name = $_SESSION['username'];

                    $_SESSION['username'] = $_POST["changeUsername"];
                    $stmt->execute();
                        
                    $stmt->close();
                }
                else {
                    echo "er zit een fout in de query";
                }
            }
        }
    }
    if(isset($_POST["change_pass"]) && isset($_POST["changePassword"]) && $_POST["changePassword"] != "") {
        
        if(mysqli_connect_errno()) {
            trigger_error("fout bij verbinding: ".$mysqli->error);
        }
        else {
            $sql = "
            UPDATE tblCustomers
            SET Password = ?
            WHERE Username = ?"
            ;

            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('ss', $pass, $username);

                $pass = $mysqli->real_escape_string($_POST["changePassword"]);
                
                $username = $_SESSION['username'];
                
                $stmt->execute();

                $stmt->close();
            }
            else {
                echo "er zit een fout in de query";
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
  <?php include "standard.php"; ?>
    
    <div class="row justify-content-center">
        <div class="col-md-5 profile">
            <h1 style="text-align: center">Profile</h1>
            <form name="form1" class="margin" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
                
                <table style="width: 100%">
                    <tr>
                        <td style="font-size: 20px; font-weight: bold;">Username:</td>
                        <td><input type=text name="changeUsername"></td>
                        <td><button type="submit" class="btn btn-default" name="change_name">change username</button></td>
                    </tr>
                    <tr>
                        <td style="font-size: 20px; font-weight: bold;">Password:</td>
                        <td><input type="password" name="changePassword"></td>
                        <td><button type="submit" class="btn btn-default" name="change_pass">change password</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="row justify-content-center" id="adminpage">
        <div class="col-md-5 profile">
            <h1 style="text-align: center">Admin</h1>
            
            <form name="frmcustomer" method="post" action="Orders.php">
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-default" name="getorders">Get Orders</button>
                </div>
            </form>

            <form name="frmcustomer" method="post" style="margin-top: 20px;" action="<?php echo $_SERVER['PHP_SELF']?>">
                <label>Username or email:</label>
                <input type="text" name="customer">
                <button type="submit" class="btn btn-default" name="search">search</button>
            </form>
            <table class="tab" style="background-color: white;">
                <tr>
                    <td>
                        <b>ID</b>
                    </td>
                    <td>
                        <b>Name</b>
                    </td>
                    <td>
                        <b>Email</b>
                    </td>
                    <td>
                        <b>Edit</b>
                    </td>
                </tr>
                </table>
                <div id="scrollbar" style="margin-bottom: 15px;">
                <table class="tab" style="background-color: white;">
            <?php
                if(mysqli_connect_errno()) {
                    trigger_error("Error when connecting: ".$mysqli->error);
                }
                else {
                    if(isset($_POST["search"]) && isset($_POST["customer"]) && $_POST["customer"] != "") {
                        if(mysqli_connect_errno()) {
                            trigger_error('fout bij verbinding'.$mysqli->error);
                        }
                        
                        if(strpos($_POST["customer"], '@') !== false) {
                            $sql = "
                            SELECT CustomerID, Username, Email
                            FROM tblCustomers
                            WHERE Email LIKE ?
                            ORDER BY CustomerID
                            ";  
                        }
                        else {
                            $sql = "
                            SELECT CustomerID, Username, Email
                            FROM tblCustomers
                            WHERE Username LIKE ?
                            ORDER BY CustomerID
                            ";  
                        }

                        if($stmt = $mysqli->prepare($sql)) {
                            $stmt->bind_param('s', $zoek);

                            $zoek = $_POST["customer"]."%";
                            if(!$stmt->execute()) {
                                echo "het uitvoeren is mislukt: ".$stmt->error."in query ".$sql;
                            }
                            else {
                                $stmt->bind_result($id,$naam,$email);

                                while($stmt->fetch()) {
                                    echo "<tr><td>".$id."</td><td>".$naam."</td><td>".$email."</td><td>";
                    ?>
                                <form name="form1" method="post" action="EditUser.php?action=edit&customerid=<?php echo $id;?>">
                                    <input type="submit" name="btnedit" id="action" value="Edit">
                                </form>
                    <?php
                                    echo "</td></tr>";
                                }
                            }
                            $stmt->close();
                        }
                        else {
                            echo "er is een fout in query: ".$mysqli->error;
                        }
                    }
                }
            ?>
            </table>
            </div>
        </div>
    </div>
    

  <!-- /Start your project here-->

  <!-- SCRIPTS -->
  <script type="text/javascript">

</script>
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
