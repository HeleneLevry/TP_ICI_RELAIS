<?php include "session.php"; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="./style.css" />
        <title>Commande</title>
    </head>
    <body>
        <header><div class="center"><h1>Point Relais</h1></div></header>
        <form method="POST" enctype="multipart/form-data" action="do.commande.php">
            <div class="colonne">
                <label for="address"> Adresse : </label>
                <input id="address" type="text" name="address" maxlength="200" required>
                <label for="ZIPCODE"> Code Postal : </label>
                <input id="ZIPCODE" type="text" name="ZIPCode" required maxlength="5" />
                <label for="city"> Ville : </label>
                <input id="city" type="text" name="city" required maxlength="20" />
                <label for="date_from">Date de livraison :</label>
                <input type="date" id="date_from" name="date_from" min=<?php echo(date("Y-m-d")); ?> max=<?php  $datemax  = mktime(0, 0, 0, date("m")  , date("d")+9, date("Y")); echo date('Y-m-d', $datemax); ?> >
                <div class="sub">
                    <input type="submit" value="Valider">
                </div>
            </div>
        </form>
    </body>
</html>