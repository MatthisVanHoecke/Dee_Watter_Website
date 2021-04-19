<?php
    include 'code.php';

    $bool = true;

    $sql = "
    DELETE FROM tblunsavedorders
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
?>