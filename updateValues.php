<?php
    include "code.php";

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
        switch($arr[6]) {
            case "0":
                $stat = "In Queue";
                break;
            case "1":
                $stat = "In Progress";
                break;
            case "2":
                $stat = "Done";
                break;
            case "3":
                $stat = "In Process";
                break;
        }
        if($arr[3] == "0") {
            $extra = 0;
        }
        else {
            $extra = 1;
        }
        $file = $customid;
        
        if(!$stmt->execute()) {
            echo "Fail: ".$stmt->error."in query ".$sql;
        }
        else {
            if(isset($_FILES['file']['name'])){

                /* Getting file name */
                $filename = $_FILES['file']['name'];
             
                /* Location */
                $location = "References/".$customid.".jpg";
             
                /* Valid extensions */
                $valid_extensions = array("jpg","jpeg","png");
             
                $response = 0;
                /* Upload file */
                if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
                    $response = $location;
                }
             }
             echo "Success";
        }
        $stmt->close();
    }
    else {
        echo "er zit een fout in de query: ".$mysqli->error;
    }
?>