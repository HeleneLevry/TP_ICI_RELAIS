<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="./style.css" />
        <title>Resultat</title>
    </head>
    <body>
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