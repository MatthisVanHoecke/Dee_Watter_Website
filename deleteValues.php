<?php
    include 'code.php';

    $bool = true;

    $sql = "
    DELETE FROM tblorderlines
    WHERE OrderID = ?
    ";

    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('i', $orid);

        $orid = $mysqli->real_escape_string($_GET["id"]);

        if(!$stmt->execute()) {
            $bool = false;
        }
        $stmt->close();
    }

    $sql = "
    DELETE FROM tblorders
    WHERE OrderID = ?
    ";

    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('i', $orid);

        $orid = $mysqli->real_escape_string($_GET["id"]);

        if(!$stmt->execute()) {
            $bool = false;
        }
        $stmt->close();
    }

    if($bool == true) {
        echo "Success";
    }
    else {
        echo "Failed";
    }

    $orid = $mysqli->real_escape_string($_GET["id"]);

    unlink('References/'.$orid.".jpg");
?>