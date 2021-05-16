<?php
    include 'code.php';

    
    $sql = "
    SELECT l.OrderID, Description, File, Detailed, ExtraCharacterAmount, PriceByOrder, Status, ArticleID, Date, Username, Email
    FROM tblorderlines l, tblorders o, tblcustomers c
    WHERE Status != 'Done' AND Status != 'In Process' AND l.OrderID = o.OrderID AND o.CustomerID = c.CustomerID
    ORDER BY l.OrderID
    ";  

    if($stmt = $mysqli->prepare($sql)) {
        if(!$stmt->execute()) {
            echo "het uitvoeren is mislukt: ".$stmt->error."in query ".$sql;
        }
        else {
            $stmt->bind_result($id,$desc,$file,$detail,$extra,$price,$status, $artid, $date, $custom, $email);

            $str = "";
            while($stmt->fetch()) {
                $str .= $id."§".$mysqli->real_escape_string($desc)."§".$mysqli->real_escape_string($file)."§".$detail."§".$extra."§".$price."§".$status."§".$artid."§".$date."§".$custom."§".$email."æ";
            }
            echo $str;
        }
        $stmt->close();
    }
    else {
        echo $mysqli->error;
    }
?>