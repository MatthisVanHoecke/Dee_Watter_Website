<?php
    session_start();

    $mysqli = new MySQLi("localhost", "root", "", "webshopphp");
    if(isset($_POST["username"]) && $_POST["username"] != "" && isset($_POST["pass"]) && $_POST["pass"] != "" && isset($_POST["email"]) && $_POST["email"] != "") {
        
        $sql = "SELECT * FROM tblCustomers WHERE Email = '".$_POST["email"]."' OR Username = '".$_POST["username"]."'";
        
        $count = 0;
        if($stmt = $mysqli->prepare($sql)) {
                
            if(!$stmt->execute()) {
                echo "het uitvoeren van de query is mislukt: ".$stmt->error." in query ".$sql;
            }
            else {

                while($stmt->fetch()) {
                    $count++;
                }
                $stmt->close();
            }
        }
        else {
            echo "failed";
        }
        if($count == 0) {
            if(mysqli_connect_errno()) {
                trigger_error("fout be verbinding: ".$mysqli->error);
            }
            else {
                $sql = "
                INSERT INTO tblCustomers(Username, Email, Password)
                VALUES(?,?,?)
                ";

                if($stmt = $mysqli->prepare($sql)) {

                    $stmt->bind_param('sss', $username, $email, $pass);

                    $username = $mysqli->real_escape_string($_POST["username"]);
                    $email = $mysqli->real_escape_string($_POST["email"]);
                    $pass = $mysqli->real_escape_string($_POST["pass"]);

                    if(!$stmt->execute()) {
                        echo "Failed";
                    }
                    else {
                        $_SESSION["username"] = $_POST["username"];
                    }
                    $stmt->close();
                }
            }
        }
    }
    if(isset($_POST["user"]) && $_POST["user"] != "" && isset($_POST["password"]) && $_POST["password"] != "") {
        
        $sql = "SELECT Username, Password FROM tblCustomers WHERE Email = '".$_POST["user"]."' OR Username = '".$_POST["user"]."'";
        
        $count = 0;
        $name = "";
        $password = "";
        if($stmt = $mysqli->prepare($sql)) {
                
            if(!$stmt->execute()) {
                echo "het uitvoeren van de query is mislukt: ".$stmt->error." in query ".$sql;
            }
            else {
                
                $stmt->bind_result($name, $password);
                while($stmt->fetch()) {
                    $count++;
                }
                $stmt->close();
            }
        }
        else {
            echo "failed";
        }
        
        if($count == 1 && $password == $_POST["password"]) {
            if(mysqli_connect_errno()) {
                trigger_error("fout be verbinding: ".$mysqli->error);
            }
            else {
                $_SESSION["username"] = $name;
            }
        }
    }
    if(isset($_GET["actie"]) && $_GET["actie"] == "signout") {
        $_SESSION["username"] = "";
        header("Location: Home.php");
    }
?>