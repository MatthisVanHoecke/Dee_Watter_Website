<?php 
    include 'code.php';

    if(mysqli_connect_errno()) {
        trigger_error("fout be verbinding: ".$mysqli->error);
    }
    else {
        if(isset($_GET["type"]) && $_GET["type"] != "") {

            $type = $_GET["type"];

            $sql = "
            SELECT ArticlePrice, Detailed, ExtraCharacter
            FROM tblarticles
            WHERE ArticleName LIKE ?
            ";

            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param('s', $name);
    
                $name = $type;
                
                $stmt->execute();
                $stmt->bind_result($articleprice, $detailed, $extrachar);
                
                $stmt->fetch();
                
                echo $articleprice.",".$detailed.",".$extrachar;
                
                $stmt->close();
            }

        }
        else {
            
            $sql = "
            SELECT ArticlePrice, Detailed, ExtraCharacter
            FROM tblarticles
            ";
    
            if($stmt = $mysqli->prepare($sql)) {
                
                $stmt->execute();
                $stmt->bind_result($articleprice, $detailed, $extrachar);
                
                $str = "";
    
                while($stmt->fetch()) {
                    $str .= $articleprice.",".$detailed.",".$extrachar."ç";
                }
                
                echo $str;
                
                $stmt->close();
            }

        }
    }
?>