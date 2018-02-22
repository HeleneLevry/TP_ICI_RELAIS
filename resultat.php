<?php include "session.php"; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="./style.css" />
        <title>Resultat</title>
    </head>
    <body>
        <!-- <p>
            <?php 
            $result = createTable();
            print_r($result);
            ?>
        </p> -->
        <div class="row">
            <div class="colonne">
                <input id="checkBox" type="checkbox">
            </div>
            <div class="colonne">
                <div id="map"></div>
                <div class="row space-evenly">
                    <div class="colonne">
                        <p> Nom : </p>
                        <p> Adresse :</p>
                    </div>
                    <div class="colonne">
                        <p class="bold" > Heures d'ouverture :</p>                        
                    </div>
                </div>                
            </div>
        </div>
        <script>
          var map;
          function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: 48.4066556, lng: -4.4991368},
              zoom: 16
            });
          }

          function test(){
            console.log("test");
          }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-tcIo3j9FrePCRwAb5VSRHQ_IUBkiMf8&callback=initMap" async defer></script>

        <!-- <script src="https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyD8LZnDRxHPwBpGU3oiCaBekDgxjYNyCbw" async defer></script> -->
            
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
    return $result;
}

// --------------------  --------------------
?>