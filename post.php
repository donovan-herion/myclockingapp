<?php if (isset($_POST['commencer'])) {
    $start = $bdd->prepare("INSERT INTO `commencer` (`commencer`) VALUES (?);");
    $start->execute(array(date("Y-m-d H:i:s", strtotime('+2 hours'))));
    $_POST['commencer'] = NULL;
}


    
if (isset($_POST['finir'])) {
    $finish = $bdd->prepare("INSERT INTO `finir` (`finir`) VALUES (?);");
    $finish->execute(array(date("Y-m-d H:i:s", strtotime('+2 hours'))));
    $_POST['finir'] = NULL;


    $tableau = $bdd->query("SELECT * FROM commencer ORDER BY commencer DESC LIMIT 1");
    while ($row = $tableau->fetch(PDO::FETCH_ASSOC)) {
        $strtotimecommencer = strtotime($row['commencer']); 
        $strtotimefinir = strtotime(date("Y-m-d H:i:s", strtotime('+2 hours')));
        }

    
    $totalsoustrait = $bdd->prepare("INSERT INTO `total` (`total`) VALUES (?);");
    $totalsoustrait->execute(array($strtotimefinir - $strtotimecommencer));
}


if (isset($_POST['reset'])) {
    $reset = $bdd->query("TRUNCATE TABLE `commencer`;");
    $reset = $bdd->query("TRUNCATE TABLE `finir`;");
    $reset = $bdd->query("TRUNCATE TABLE `total`;");
    $_POST['reset'] = NULL;
}

?>