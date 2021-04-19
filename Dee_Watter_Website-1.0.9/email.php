<?php 

    include 'code.php';

    $user = $_GET["email"];
    if(mysqli_connect_errno()) {
        trigger_error("fout be verbinding: ".$mysqli->error);
    }
    else {
        $sql = "
        SELECT Email
        FROM tblcustomers
        WHERE Email LIKE ?
        ";

        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $name);

            $name = $user;
            
            $stmt->execute();
            $stmt->bind_result($username);

            $stmt->fetch();

            echo $username;   
            
            $stmt->close();
        }
    }
?>