<?php
    include 'code.php';
    
    if($_SESSION["username"] == "" && !isset($_GET["actie"]) && $_GET["actie"] != "signout") {
        header("location: accountError.php");
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
                $file = $mysqli->real_escape_string($_POST["upload"]);
                
                $price = $articleprice;
                
                $detailed = 0;
                $extra = 0;
                $status = "In Queue";
                $extraa = 0;
                
                $orderid = $order+1;
                
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

                $stmt->close();
            }
            else {
                echo "failed";
            }
        }
    }
?>