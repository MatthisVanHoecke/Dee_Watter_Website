<?php
    include 'code.php';

    
    $sql = "
    SELECT PriceByOrder
    FROM tblorderlines
    WHERE OrderID = ?
    ";  

    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $cid);

        $cid = $mysqli->real_escape_string($_GET["orderid"]);
        if(!$stmt->execute()) {
            echo "het uitvoeren is mislukt: ".$stmt->error."in query ".$sql;
        }
        else {
            $stmt->bind_result($price);

            $stmt->fetch();
            echo $price;
        }
        $stmt->close();
    }
?>