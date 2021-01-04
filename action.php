<?php
    session_start();
    $mysqli = new MySQLi("localhost", "root", "", "webshopphp");

    if(mysqli_connect_errno()) {
        trigger_error("fout be verbinding: ".$mysqli->error);
    }
    else {
        $sql = "
        INSERT INTO tblKlanten(Username, Email, Password)
        VALUES(?,?,?)
        ";

        if($stmt = $mysqli->prepare($sql)) {

            $stmt->bind_param('sss', $username, $email, $pass);

            $username = $mysqli->real_escape_string($_POST["username"]);
            $email = $mysqli->real_escape_string($_POST["email"]);
            $pass = $mysqli->real_escape_string($_POST["pass"]);

            if(!$stmt->execute()) {
                echo "No";
            }
            else {
                $_SESSION["username"] = $_POST["username"];
                echo "Yes";
            }
            $stmt->close();
        }
    }
?>
