<?php

// b199f3ca4155d1 login

// bdd573ee mdp

// eu-cdbr-west-03.cleardb.net host name

// heroku_622c901f7f2f05c db name

#connexion a la base de donnees

$bdd = new PDO("mysql:host=eu-cdbr-west-03.cleardb.net;dbname=heroku_622c901f7f2f05c;charset=utf8", "b199f3ca4155d1", "bdd573ee");

#condition d'envoi a la bdd

if (isset($_POST['commencer'])) {
    $start = $bdd->prepare("INSERT INTO `commencer` (`commencer`) VALUES (?);");
    $start->execute(array(date("Y-m-d H:i:s", strtotime('+2 hours'))));
    $_POST['commencer'] = NULL;
    header('Location: index.php'); //clears POST

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
    header('Location: index.php'); //clears POST
}


if (isset($_POST['reset'])) {
    $reset = $bdd->query("TRUNCATE TABLE `commencer`;");
    $reset = $bdd->query("TRUNCATE TABLE `finir`;");
    $reset = $bdd->query("TRUNCATE TABLE `total`;");
    $_POST['reset'] = NULL;
    header('Location: index.php'); //clears POST
}

?>


<!DOCTYPE html>
<html lang="en">

<!-- Cookie pour la connexion -->

<?php ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">    <title>MyClockingApp</title>
</head>

<body class="buddy" <?php if (isset($_POST['start'])) {echo 'class="modal-open"';}?> >

    <!-- titre heures minutes secondes et nom du jour -->

    <h1 class="padding"><?php $nameOfDay = date('l', strtotime(date("Y-m-d H:i:s", strtotime('+2 hours')))); echo $nameOfDay; ?></h1>
    <h1 class="padding">
        <span id="hour">-- :</span>
        <span id="minute">-- :</span>
        <span id="second">--</span>
    </h1>

    

    
    <!-- Deux boutons submit qui declenchent la condition d'envoi a la bdd alternativement -->
    <?php $entreecommencer = $bdd->query("SELECT * FROM commencer");
          $entreefinir = $bdd->query("SELECT * FROM finir");
          if ($entreecommencer->rowCount() == $entreefinir->rowCount()){ ?>
    <form action="" method="POST" class="margin d-flex justify-content-center">
        <input class="dis-commencer btn btn-primary input" type="submit" name="commencer" value="Start">
    </form>
    <?php } else { ?>
    <form action="" method="POST" class="margin d-flex justify-content-center">
        <input class="dis-finir btn btn-primary input" type="submit" name="finir" value="Stop">
    </form>
    <?php }?>
    
    <!-- Button trigger modal -->
    <div class="margin d-flex justify-content-center">
        <button type="button" class="calcul btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">
            Check Hours
        </button>
    </div>
        

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Calculate total of hours</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="d-flex align-items-center flex-column">
                            <br><label for="start">Starting date :</label>
                            <input type="date" name="start"><br>
                            
                            <label for="end">Ending date :</label>
                            <input type="date" name="end"><br>
                        </div>
                      
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary resultat" value="Results"></input>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Condition second modal -->

    <?php if (isset($_POST['start'])) {?>

        <?php $s = $_POST['start'];
        $e = $_POST['end'];

        $scondition = $s . ' 02:00:00'; #la db est deux heures en avance
        $econdition = $e . ' 23:59:59'; #impossible de faire $e +1 et 02:00:00
        

        $additiontotale = 0;

        $total = $bdd->query("SELECT * FROM commencer JOIN total ON commencer_id = total_id WHERE commencer > '$scondition' AND commencer < '$econdition' ORDER BY commencer DESC");
        while ($row = $total->fetch(PDO::FETCH_ASSOC)) {
            $additiontotale = $additiontotale + $row['total'];
            $fulltimeadditiontotale = floor($additiontotale / 3600) . gmdate(":i:s", $additiontotale % 3600);
        }?>

        <div class="modal fade show" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-modal="true" <?php if (!isset($_POST['fermer'])) { echo 'style="display: block;"' ; }?>>
            <div class="modal-dialog modal-dialog-centered" role="document">
            
            <div class="modal-content">
                <div class="modal-header header-modal">
                    <h5>My total</h5><br>
                </div>
                    <div class="modal-body text-center">
                        
                        <p>From <?php echo $s;?></p>
                        <p>To <?php echo $e;?> (included)</p>
                        <p style="font-weight: bold;"><?php echo $fulltimeadditiontotale;?></p>

                        <form action="" method="POST">
                            <input class="btn btn-primary fermer" type="submit" value="Close" name="fermer">
                        </form>
                    </div>
                </div>
            </div>
        </div>  
    <? } ?>
    
    <!-- recap des heures -->
    
    
    <div class="flex-down">
        <div class="left">

            <?php $tableau = $bdd->query("SELECT * FROM commencer ORDER BY commencer DESC");?>
             <p class="height">Start</p> 
            <?php while ($row = $tableau->fetch(PDO::FETCH_ASSOC)) { ?>

                <!-- Ajouter date dans la colonne & augmenter le total de secondes dans commencer -->

                <p class="height"><?php echo $row['commencer'] ?></p>
            <?php } ?>

        </div>


        <div class="middle">

            <?php $tableau = $bdd->query("SELECT * FROM finir ORDER BY finir DESC");?>
            <p class="height">End</p> 
            <?php while ($row = $tableau->fetch(PDO::FETCH_ASSOC)) { ?>

                <!-- Ajouter date dans la colonne -->

                <p class="height"><?php echo $row['finir'] ?></p>
            <?php } ?>

        </div>

        <div class="right">
            <?php
            $total = $bdd->query("SELECT * FROM commencer c JOIN total t ON commencer_id = total_id ORDER BY commencer DESC");?>
            <p class="height">Total</p> 
            <?php while ($row = $total->fetch(PDO::FETCH_ASSOC)) { ?>

                <?php $DifferenceSecondes = $row['total'];

                $fulltimedifference = floor($DifferenceSecondes / 3600) . gmdate(":i:s", $DifferenceSecondes % 3600);


                ?>
                <p class="height"><?php echo $fulltimedifference; ?></p>
            <?php } ?>
        </div>
    </div>

    <form action="" method="POST" class="d-flex justify-content-center">
        <input class="btn btn-danger danger" type="submit" name="reset" value="Reset" style="display: none;"> 
        <!-- desactiver le display none pour reset la bdd -->
    </form>

    <!-- Fin recap des heures -->

    
    <?php if (isset($_POST['start'])) {echo '<div class="modal-backdrop fade show"></div>';}?>


<script src="app.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</body>

</html>