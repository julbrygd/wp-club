<?php
    $defaultFormat = "";
    $module = \Club\Club::getInstance()->getModules()->getModuleInstance("calendar");
    if($module != NULL){
        $defaultFormat = $module->getDateFormat();
    }
?>

<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Termine</h1>
        <form class="form-horizontal">
            <div class="form-group">
                <label for="txtTitle" class="col-sm-2 control-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="txtTitle" placeholder="Titel">
                </div>
            </div>
            <div class="form-group">
                <label for="txtDescription" class="col-sm-2 control-label">Beschreibung</label>
                <div class="col-sm-10">
                    <div id="txtDescription"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="txtFrom" class="col-sm-2 control-label">Datum</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-6"><label for="dateFrom">Von</label></div>
                        <div class="col-sm-6"><label for="dateTo">Bis</label></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="dateFrom"></div>
                        </div>
                        <div class="col-sm-6">
                            <div id="dateTo"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <label for="txtPlace" class="col-sm-2 control-label">Ort</label>
                <div class="col-sm-10">
                    <input type="text" id="txtPlace" class="form-control" /><br />
                    <input id="pac-input" class="controls" type="text" placeholder="Ort Suchen">
                    <div id="map" style="height: 500px; width: 100%"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">

                    <br />  
                    <button type="button" id="btnSave" class="btn btn-default">Speichern</button>
                </div>
            </div>
    </div>


</div>

<link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">

<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.7.3/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.7.3/summernote.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/i18n/jquery-ui-i18n.min.js"></script>
<script src="<?php echo Club\Club::getInstance()->plugin_url ?>/js/jquery.datetimepicker.full.min.js"></script>
<link href="<?php echo Club\Club::getInstance()->plugin_url ?>/css/jquery.datetimepicker.min.css" rel="stylesheet" />
<link href="<?php echo Club\Club::getInstance()->plugin_url ?>/css/googlemaps.css" rel="stylesheet" />
<script type="text/javascript">
    var $ = jQuery.noConflict();
    var dateFormat = "<?php echo $defaultFormat ?>";
    var markers = [];
    var savedPlace = false;
    $(document).ready(function () {
        $('#txtDescription').summernote();
        $('#btnSave').on('click', function () {
            var data = {
                title: $("#txtTitle").val(),
                desc: $("#txtDescription").summernote('code'),
                from: $('#dateFrom').val(),
                to: $('#dateTo').val(),
                "savedPlace": savedPlace
            };
            
            if(markers.length >= 1){
                data["lng"] = markers[0].position.lng();
                data["lat"] = markers[0].position.lat();
            }
            if(!savedPlace){
                var a = confirm("Soll der Ort gespeichert werden?");
                if(a){
                    data["place"] = window.prompt("Ortsname: ");
                    data["savePlace"] = true;
                }
            }
            alert(JSON.stringify(data));
            club_ajax_post("calendar_save_event", data, function(resp){
               alert(JSON.stringify(resp)); 
            });
        });
        $('#dateFrom').datetimepicker({
            format: dateFormat,
            inline: true,
            lang: 'de'
        });
        $('#dateTo').datetimepicker({
            format: dateFormat,
            inline: true,
            lang: 'de'
        });


    });

    function initMap() {
        var mapDiv = document.getElementById('map');
        var map = new google.maps.Map(mapDiv, {
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var initialLocation = undefined;
        // Try W3C Geolocation (Preferred)
        if (navigator.geolocation) {
            browserSupportFlag = true;
            navigator.geolocation.getCurrentPosition(function (position) {
                initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                map.setCenter(initialLocation);
            }, function () {
                initialLocation = new google.maps.LatLng(47.55814, 7.58769);
            });
        }
        // Browser doesn't support Geolocation
        else {
            initialLocation = new google.maps.LatLng(47.55814, 7.58769);
        }

        map.setCenter(initialLocation);

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });

        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: place.geometry.location
                }));

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });

    }

</script>

