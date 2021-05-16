<?php
    include 'code.php';

    
    $sql = "
    SELECT l.OrderID, Description, File, Detailed, ExtraCharacterAmount, PriceByOrder, Status, ArticleID
    FROM tblorderlines l, tblorders o
    WHERE l.OrderID = o.OrderID AND CustomerID = ? AND Status != 'In Process'
    ORDER BY l.OrderID
    ";  

    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $cid);

        $cid = $mysqli->real_escape_string($_GET["customerid"]);
        if(!$stmt->execute()) {
            echo "het uitvoeren is mislukt: ".$stmt->error."in query ".$sql;
        }
        else {
            $stmt->bind_result($id,$desc,$file,$detail,$extra,$price,$status, $artid);

            $str = "";
            while($stmt->fetch()) {
                $str .= $id."§".$mysqli->real_escape_string($desc)."§".$mysqli->real_escape_string($file)."§".$detail."§".$extra."§".$price."§".$status."§".$artid."æ";
            }
            echo $str;
        }
        $stmt->close();
    }
?>