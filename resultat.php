<?php include "session.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="./style.css" />
    <title>Resultat</title>
</head>
<body>
    <?php $result = createTable();?>
    <div class="row">
        <div class="colonne">
            <form>
                <p>Veuillez choisir votre point relais :</p>
                <div class="colonne">
                    <?php
                    // On crée le formulaire avec les ratios
                    foreach ($result as $key => $value) { 
                        echo'
                        <input type="radio" id="'.$key.'" name="test"  value="'.$value[1].'" onclick="info('.$key.','.count($result).')">
                        <label for="'.$key.'">'.$value[1].'</label>
                        ';  
                    };
                    ?>
                </div>
            </form>

        </div>
        <div class="colonne">
            <div id="map"></div>
            <div class="row space-evenly">
                <?php
                // on crée l'encart informations de chaque relais
                foreach ($result as $key => $value) {
                    echo('<div id="div'.$key.'" class="colonne center" style="display:none;">');
                    echo('<div id="nom'.$key.'" class="bold">'.$value[1].' </div>');                             
                    echo('<div id="adresse1'.$key.'" > '.$value[2].' </div>');
                    echo('<div id="adresse2'.$key.'" > '.$value[3].' </div>');
                    echo('<div id="adresse3'.$key.'" > '.$value[4].' </div>');
                    echo('<div id="CP'.$key.'" >'.$value[5].' '.$value[6].' </div>');
                    echo('</div>');
                };
                ?>            
            </div>
            <div class="colonne">
                <p id="titre" class="bold center" style="display: none;" > Heures d'ouverture :</p>
                <?php
                //On crée le planning d'ouverture de chaque relais 
                $semaine = array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
                foreach ($result as $key => $value) {
                    echo('<div class="row" style="display: none;" id="horraire'.$key.'">');
                    $nombre=0;
                    $i=0;
                    foreach ($value[9] as $cle => $valeur) {                                    
                        if($cle%2==0){
                            echo('<div class="colonne marge10">');
                            echo('<div class="bold">'.$semaine[$i].' :</div> ');
                            $i++;
                        }
                        echo( '<div>'.$valeur[1].' '.$valeur[2].'</div>');
                        if($cle%2!=0){
                            echo('</div>');
                        }
                        $nombre++;
                    };
                    if($nombre%2!=0){
                        echo('</div>');  
                    }
                    echo('</div>');                                
                };
                ?>                    
            </div>
        </div>                
    </div>
</div>
<script>
    function getQueryVariable(variable)
    {
     var query = window.location.search.substring(1);
     var vars = query.split("&");
     for (var i=0;i<vars.length;i++) {
         var pair = vars[i].split("=");
         if(pair[0] == variable){return pair[1];}
     }
     return(false);
    }

 var map;

 function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: Number(getQueryVariable("lat")), lng: Number(getQueryVariable("lng"))},
        zoom: 13
    });

    // Récupération de $result
    var locations = <?php echo json_encode($result)?>;
    var locations = Object.keys(locations).map(function(k) { return locations[k] });

     var contentString = '<div id="content">'+
            '<div id="siteNotice">'+'coucou'
            '</div>'+'</div>';

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    var infowindows= new Object();

    locations.forEach(function(element,index) {
        var lat= element[8][0].replace(',','.');
        var lng= element[7][0].replace(',','.');
        var markerPosition = {lat: parseFloat(lat), lng: parseFloat(lng)};
        console.log(markerPosition);

        var infowindow = new google.maps.InfoWindow({
            content: '<div id="content">'+
            '<div id="siteNotice">'+element[1][0]+ '</div>'+'</div>'
        });
        infowindows[element[1][0]]=infowindow;

        var marker = new google.maps.Marker({
            position : markerPosition,
            title: element[1][0],
            map: map
        });
        // aJout de l'evenement sur le bouton ratio correspondant
        document.getElementById(index).addEventListener("click", (function() {
            infowindows[element[1][0]].open(map, marker);
        }));
    });
        //Marquer de position du client
    var markerPosition = {lat: Number(getQueryVariable("lat")), lng: Number(getQueryVariable("lng"))};
    var marker = new google.maps.Marker({
        position : markerPosition,
        title: "Ma position",
        map: map
    });
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-tcIo3j9FrePCRwAb5VSRHQ_IUBkiMf8&callback=initMap" async defer></script>

<script>
    function info(num,max){
        var i=0;
        var titre = document.getElementById("titre");
        titre.style="display:flex";
        for(i=0;i<max;i++){
            var heure = document.getElementById("horraire"+i);
            var div = document.getElementById("div"+i);
            if (i==num){
                div.style="display:flex;";
                heure.style="display:flex;";
            }else{
                div.style="display:none;";
                heure.style="display:none;";
            }
        }
    }
</script>
</body>
</html>

<?php

function test(){
    return $result[1][7];
}
// -------------------- FUNCTIONS --------------------

// ----- Treatment -----
// createTable
function createTable(){
    $outputFile = "./output.xml";
    $xml = simplexml_load_file($outputFile);
    $i=0;
    $result= array();
    foreach ($xml->PUDO_ITEMS->PUDO_ITEM as $pudo_item) {
        if ($pudo_item['active'] == "true"){
            $distance = $pudo_item->DISTANCE;
            $name = $pudo_item->NAME;
            $address1 = $pudo_item->ADDRESS1;
            $address2 = $pudo_item->ADDRESS2;
            $address3 = $pudo_item->ADDRESS3;
            $zipcode = $pudo_item->ZIPCODE;
            $city = $pudo_item->CITY;
            $longitude = $pudo_item->LONGITUDE;
            $latitude = $pudo_item->LATITUDE;
            $opening_hours_items = $pudo_item->OPENING_HOURS_ITEMS;
            $j=0;
            $opening_hours=array();
            foreach ($opening_hours_items->OPENING_HOURS_ITEM as $opening_hours_item) {
                $day_id = $opening_hours_item->DAY_ID;
                $start_tm = $opening_hours_item->START_TM;
                $end_tm = $opening_hours_item->END_TM;
                ${'opening_hours'.$j} = array($day_id, $start_tm, $end_tm);
                array_push($opening_hours, ${'opening_hours'.$j});
                $j++;
            }

            ${'table'.$i} = array($distance, $name, $address1, $address2, $address3, $zipcode, $city, $longitude, $latitude, $opening_hours);
            array_push($result, ${'table'.$i});
            $i++;
        }
    }
    return $result;
}
// findCoord
function findCoord(){
    global $result;
    $tableCoord = array();
    for($i=0; $i<count($result); $i++) {
        $coordinates = array(str_replace(',','.',$result[$i][7][0]),str_replace(',','.',$result[$i][8][0]));
        print_r($result[$i][7][0]);
        array_push($tableCoord, $coordinates);
    }
    foreach ($tableCoord as $coords) {
        ?>
        <script>
            var lat = <?php echo $tableCoord[0]; ?>;
            var long = <?php echo $tableCoord[1]; ?>;
            var markerPosition = {lat: lat, lng: lng};
            var marker = new google.maps.Marker({
                position : markerPosition,
                map: map
            });
        </script>
    <?php
    }
}

// --------------------  --------------------
?>