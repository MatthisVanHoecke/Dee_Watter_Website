<?php
    include 'code.php';

    $sql = "
    UPDATE tblorderlines SET Detailed = ?, Description = ?, ExtraCharacterAmount = ?, PriceByOrder = ?, Status = ?, File = ?, ExtraCharacter = ?
    WHERE OrderID = ?
    ";

    if($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('isidssii', $detai, $description, $extr, $pric, $stat, $file, $extra, $customid);

        $arr = explode(",", $mysqli->real_escape_string($_GET["string"]));

        $customid = $arr[0];
        $detai = $arr[1];
        $description = $arr[2];
        $extr = $arr[3];
        $pric = $arr[4];
        $extra = 0;
        if($arr[6] == "0") {
            $stat = "In Queue";
        }
        else {
            $stat = "In Progress";
        }
        if($arr[3] == "0") {
            $extra = 0;
        }
        else {
            $extra = 1;
        }
        $file = $arr[5];
        
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