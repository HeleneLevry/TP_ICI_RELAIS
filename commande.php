<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="./style.css" />
    <title>Commande</title>
</head>

<body>
    <header><div class="colonneBody"><h1>Point Relais</h1></div></header>
     <!-- Error messages -->
    <?php
    if (isset($_GET['error'])) {
        printError();
    }
    ?>
    <form method="POST" enctype="multipart/form-data" action="do.commande.php">
        <div class="colonne75">
            <label for="address"> Adresse : </label>
            <input type="text" id="address" class="inputText" name="address" maxlength="200" required>
            <label for="ZIPCODE"> Code Postal : </label>
            <input type="text" id="ZIPCODE" class="inputText" name="ZIPCode" required maxlength="5" />
            <label for="city"> Ville : </label>
            <input type="text" id="city" class="inputText" name="city" required maxlength="20" />
            <label for="date_from">Date de livraison :</label>
            <input type="date" id="date_from" class="inputText" name="date_from" value=<?php echo(date("Y-m-d")); ?> min=<?php echo(date("Y-m-d")); ?> max=<?php  $datemax  = mktime(0, 0, 0, date("m")  , date("d")+9, date("Y")); echo date('Y-m-d', $datemax); ?> >
            <div class="rightAlign">
                <input type="submit" class="button" value="Valider">
            </div>
        </div>
    </form>
</body>

</html>

<!-- ____________________ FUNCTIONS ____________________ -->
<!-- printError -->
<?php
function printError(){
    switch($_GET['error']) {
        case 'missingArg':
        echo '<p class="err">Some fields are missing</p>';
        break;
        case 'errGetCoord1':
        echo '<p class="err">Fail to get address coordinates (issue to connect)</p>';
        break;
        case 'errGetCoord2':
        echo '<p class="err">Fail to get address coordinates (issue to format)</p>';
        break;
        case 'errRecordInFile1':
        echo '<p class="err">Fail to get relays coordinates (issue to connect)</p>';
        break;
        case 'errRecordInFile2':
        echo '<p class="err">Fail to get relays coordinates (issue to format)</p>';
        break;
        case 'unknow':
        echo '<p class="err">Error unknown</p>';
        break;
        default: 
        echo '<p class="err">An error is detected but not identified</p>';
    }
}
?>