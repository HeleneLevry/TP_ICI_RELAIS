<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="./style.css" />
    <title>Resultat</title>
</head>

<body>

    <!-- Exploit xml -->
    <?php $result = createTable();?>

    <div class="rowBody">

        <div class="colonneBody col300">

            <form>
                <fieldset>
                    <legend>
                        Veuillez choisir votre point relais :
                    </legend>
                    <div class="colonne">
                        <?php
                            // On crée le formulaire avec les radios
                        foreach ($result as $key => $value) { 
                            echo('
                                <label for="'.$key.'">
                                <input class ="radio" type="radio" id="'.$key.'" name="test"  value="'.$value[1].'" onclick="info('.$key.','.count($result).')">
                                '.ucwords(strtolower($value[1])).'
                                </label>
                                ');  
                        }
                        ?>
                    </div>
                </fieldset>
            </form>
            <div class="row">
                <input class="button" type="button" value="Nouvelle recherche" onclick="window.location.href='commande.php'">
            </div>

        </div>

        <div class="colonneBody flexStart">

            <div id="map" >
            </div>

            <div class="row">
                <?php
                    // on crée l'encart informations de chaque relais
                    foreach ($result as $key => $value) {
                        echo('
                            <div id="adresse'.$key.'" class="divAdresse" style="display:none;">
                                <hr>
                                <div>
                                    <h3 id="nom'.$key.'">'.ucwords(strtolower($value[1])).'</h3>
                                    <p id="adresse1'.$key.'" > '.strtolower($value[2]).' </p>
                                    <p id="adresse2'.$key.'" > '.strtolower($value[3]).' </p>
                                    <p id="adresse3'.$key.'" > '.strtolower($value[4]).' </p>
                                    <p id="CP'.$key.'" >'.$value[5].' '.ucwords(strtolower($value[6])).' </p>
                                </div>
                                <hr>
                            </div>');
                    };
                ?>          
            </div>

            <h3 id="titre" style="display: none;" > Heures d'ouverture :</h3>
            
            <div class="row">
                <?php
                //On crée le planning d'ouverture de chaque relais 
                $jour = array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
                foreach ($result as $key => $value) {
                    echo('<div id="horaire'.$key.'" class="row" style="display: none;">');
                    $nombre=0;
                    $i=0;
                    foreach ($value[9] as $cle => $valeur) {                                    
                        if($cle%2==0){
                            echo('
                                <div class="colonneJour">
                                    <h4>'.$jour[$i].' :</h4> ');
                            $i++;
                        }
                        echo('<p>'.$valeur[1].' - '.$valeur[2].'</p>');
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

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-tcIo3j9FrePCRwAb5VSRHQ_IUBkiMf8&callback=initMap" async defer></script>

        <script>
        //-- Global variables
        var map;
        //-- getQueryVariable
        function getQueryVariable(parameterName) {
            // Get URL
            var query = window.location.search.substring(1);
            // Record by spliting on &
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++) {
                // Record by splitting on =
                var pair = vars[i].split("=");
                // return value of parameter "parameterName"
                if(pair[0] == parameterName){
                    return pair[1];
                }
            }
            return false;
        }
        //-- initMap
        function initMap() {
            // Create map
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: Number(getQueryVariable("lat")), lng: Number(getQueryVariable("lng"))},
                zoom: 13
            });
            // Récupération de $result by json to javascript variable
            var locations = <?php echo json_encode($result)?>;
            var locations = Object.keys(locations).map(function(k){ return locations[k] 
            });
            // Create object of infos to show
            var infowindows= new Object();
            // Create group of marker to center
            var bounds = new google.maps.LatLngBounds();
            // Get lat and lng for each result (and replace 1,0 by 1.0)
            locations.forEach(function(element,index){
                // Get lat and lng for each result (and replace 1,0 by 1.0)
                var lat= element[8][0].replace(',','.');
                var lng= element[7][0].replace(',','.');
                // Record position of the marker for each result
                var markerPosition = {lat: parseFloat(lat), lng: parseFloat(lng)};
                // Create info to open with name of the result for each marker
                var infowindow = new google.maps.InfoWindow({
                    content: '<div id="content">'+
                    '<div id="siteNotice">'+element[1][0]+ '</div>'+'</div>'
                });
                infowindows[element[1][0]]=infowindow;
                // Create marker for each result
                var marker = new google.maps.Marker({
                    position : markerPosition,
                    title: element[1][0],
                    map: map
                });
                // add to group of marker to center
                bounds.extend(marker.position);
                // Ajout de l'evenement sur le bouton ratio correspondant
                document.getElementById(index).addEventListener(
                    "click", (function() {
                        infowindows[element[1][0]].open(map, marker);
                    })
                );
            });
            // Marqueur de position du client
            var markerPosition = {lat: Number(getQueryVariable("lat")), lng: Number(getQueryVariable("lng"))};
            var marker = new google.maps.Marker({
                position : markerPosition,
                title: "Ma position",
                map: map
            });
            // add to group of marker to center
            bounds.extend(marker.position);
            // auto-zoom
            map.fitBounds(bounds);
            // auto-center
            map.panToBounds(bounds);
        }
        //-- info
        function info(num,max){
            // Afficher la section des heures d'ouverture
            var titre = document.getElementById("titre");
            titre.style="display:flex";
            for(var i=0;i<max;i++){
                // Element adresse et element horaire
                var adresse = document.getElementById("adresse"+i);
                var heure = document.getElementById("horaire"+i);
                // afficher adresse et heure du result i, cacher les autres      
                if (i==num){
                    adresse.style="display:flex;";
                    heure.style="display:flex;";
                }else{
                    adresse.style="display:none;";
                    heure.style="display:none;";
                }
            }
        }
    </script>

</div>
</body>
</html>

<?php
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
// --------------------  --------------------
?>