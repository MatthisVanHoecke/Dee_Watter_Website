<?php
    include "code.php";

    $sql = "
    UPDATE tblorderlines SET Status = ?
    WHERE OrderID = ?
    ";

    $orderid = $_GET["orderid"];
    
    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('si', $queue, $orderid);
        
        $queue = "In Queue";
        if(!$stmt->execute()) {
            echo "Fail: ".$stmt->error."in query ".$sql;
        }
        else {
             echo "Success";
        }
        $stmt->close();
    }
    else {
        echo "er zit een fout in de query: ".$mysqli->error;
    }
?>