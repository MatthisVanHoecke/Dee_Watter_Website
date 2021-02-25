<?php 

    include 'code.php';

    $user = $_GET["username"];
    $email = $_GET["email"];
    if(mysqli_connect_errno()) {
        trigger_error("fout be verbinding: ".$mysqli->error);
    }
    else {
        $sql = "
        SELECT Username, Email, Password
        FROM tblcustomers
        WHERE Username = ? OR Email = ?
        ";

        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('ss', $name, $mail);

            $name = $user;
            $mail = $email;
            
            $stmt->execute();
            $stmt->bind_result($username, $emailadress, $passw);

            $stmt->fetch();
            $pass_verify = "no";
            if(password_verify($_GET["pass"], $passw)) {
                $pass_verify = "yes";
            }

            echo $username.",".$emailadress.",".$pass_verify;   
            
            $stmt->close();
        }
    }
?>