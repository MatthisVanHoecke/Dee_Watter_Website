<?php 
    include 'code.php';

    $type = $_GET["type"];
    if(mysqli_connect_errno()) {
        trigger_error("fout be verbinding: ".$mysqli->error);
    }
    else {
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
?>