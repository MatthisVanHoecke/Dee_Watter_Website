<?php
    include 'code.php';

    
    $sql = "
    SELECT OrderID, Description, File, Detailed, ExtraCharacterAmount, PriceByOrder
    FROM tblunsavedorders
    WHERE CustomerID = ?
    ORDER BY CustomerID
    ";  

    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('s', $cid);

        $cid = $mysqli->real_escape_string($_GET["customerid"]);
        if(!$stmt->execute()) {
            echo "het uitvoeren is mislukt: ".$stmt->error."in query ".$sql;
        }
        else {
            $stmt->bind_result($id,$desc,$file,$detail,$extra,$price,$status);

            $str = "";
            while($stmt->fetch()) {
                $str .= $id."§".$mysqli->real_escape_string($desc)."§".$mysqli->real_escape_string($file)."§".$detail."§".$extra."§".$price."æ";
            }
            echo $str;
        }
        $stmt->close();
    }
?>