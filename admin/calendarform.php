<?php

$defaultFormat = "";
$momentFormat = "";
$module = \Club\Club::getInstance()->getModules()->getModuleInstance("calendar");
if ($module != NULL) {
    $defaultFormat = $module->getDateFormat();
    $momentFormat = $module->getDateFormat("moment");
}
?>

<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Termine</h1>
        <form class="form-horizontal">
            <div class="form-group" id="divTitle" class="can-have-error">
                <label for="txtTitle" class="col-sm-2 control-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control can-have-error" id="txtTitle" placeholder="Titel" data-divid="divTitle" />
                    <span id="divTitleHelp" class="help-block"></span>
                </div>
            </div>
            <div class="form-group" id="divDesc">
                <label for="txtDescription" class="col-sm-2 control-label">Beschreibung</label>
                <div class="col-sm-10">
                    <?php //<div id="txtDescription"></div> ?>
                    <?php wp_editor("", "txtDescription", array("drag_drop_upload" => true)); ?>
                    <br />
                    <span id="divDescHelp" class="help-block"></span>
                </div>
            </div>
            <div class="form-group" id="divDate">
                <label for="txtFrom" class="col-sm-2 control-label">Datum</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-6"><label for="dateFrom">Von</label></div>
                        <div class="col-sm-6"><label for="dateTo">Bis</label></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <!--<div id="dateFrom"></div>-->
                            <input type="hidden" id="dateFrom" value="" />
                            <span id="dateFrom_picker"></span><br />
                            <span id="divFromHelp" class="help-block"></span>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" id="dateTo" value="" />
                            <span id="dateTo_picker"></span><br />
                            <span id="divToHelp" class="help-block"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" id="divOrt" class="can-have-error">
                <label for="txtPlace" class="col-sm-2 control-label">Ort</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-10">
                            <span id="txtPlaceChoose">
                                <select class="form-control can-have-error combobox" id="txtPlace"  data-divid="divOrt">
                                    <option value="">Bitte Ort auswählen ...</option>
                                    <?php foreach (\Club\Admin\Calendar\Place::getAll() as $place) { ?>
                                        <option value="<?php echo $place->getUuid() ?>"><?php echo $place->getName() ?></option>
                                    <?php } ?>
                                </select>
                            </span>
                            <input type="text" class="form-control can-have-error" placeholder="Ort name" data-divid="divOrt" id="txtPlaceNew" />
                            <br />
                            <span id="divOrtHelp" class="help-block">Ort ausw&auml;hlen oder neuer Hinzuf&uuml;gen</span>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" id="btnNewPlace" class="btn btn-default">Neuer Ort</button>
                        </div>
                    </div>
                    <br />
                    <div id="divMap">
                        <br />
                        <input id="pac-input" class="controls" type="text" placeholder="Ort Suchen">
                        <div id="map" style="height: 500px; width: 100%"></div>
                    </div>
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

<link href="<?php echo Club\Club::getInstance()->plugin_url ?>/css/googlemaps.css" rel="stylesheet" />

<script type="text/javascript">
    var $ = jQuery.noConflict();
    var dateFormat = "<?php echo $defaultFormat ?>";
    var momentFormat = "<?php echo $momentFormat ?>";
    var markers = [];
    var useMarker = false;
    var errorHelper = {
        "divTitle": {
            text: "Der Text darf nicht leer sein",
            helper: ["divTitleHelp"]
        },
        "divDesc": {helper: ["divDescHelp"]},
        "divDate": {helper: ["divToHelp", "divFromHelp"]},
        "divOrt": {
            text: "Der Ort darf nicht leer sein",
            helper: ["divOrtHelper"]
        }
    };
    var errorState = "has-error";
    var newPlace = false;

    $(document).ready(function () {
        var now = moment();
        now.set('minute', 0);
        now.set('second', 0);
        now.set('millisecond', 0);
        var from = now.unix();
        now.add(8, 'h');
        var to = now.unix();

        $("#txtPlaceNew").hide();
        function checkForm(sel, text) {
            var div = $(sel).data("divid");
            var value = $(sel).val();
            var text = typeof text !== 'undefined' ? text : errorHelper[div]["text"];
            var helper = errorHelper[div]["helper"];
            if (value === undefined || value === "") {
                if (!$("#" + div).hasClass(errorState)) {
                    $("#" + div).addClass(errorState);
                }
                $("#" + helper).html(text);
            } else {
                $("#" + helper).html("");
                if ($("#" + div).hasClass(errorState)) {
                    $("#" + div).removeClass()(errorState);
                }
            }
        }

        addDescCheck();

        function addDescCheck() {
            if (tinymce.activeEditor !== null) {
                addDescCheckInternla();
            } else {
                window.setTimeout(addDescCheck, 1000);
            }
        }

        function addDescCheckInternla() {
            tinymce.activeEditor.onChange.remove(checkDesc);
            tinymce.activeEditor.onChange.add(checkDesc);
        }

        function checkDesc() {
            var val = tinymce.activeEditor.getContent();
            if (val === undefined || val === "") {
                $("#divDescHelp").html("Die Beschreibung darf nicht leer sein");
                if (!$("#divDesc").hasClass(errorState)) {
                    $("#divDesc").addClass(errorState);
                }
            } else {
                $("#divDescHelp").html("");
                if ($("#divDesc").hasClass(errorState)) {
                    $("#divDesc").removeClass(errorState);
                }
            }
        }

        $("input.can-have-error").on("blur", function () {
            checkForm(this);
        });

        $('#btnSave').on('click', function () {
            var mfrom = moment($('#dateFrom_picker').datetimepicker('getDate'));
            var data = {
                title: $("#txtTitle").val(),
                desc: tinymce.activeEditor.getContent(),
                from: mfrom.unix(),
                to: moment($('#dateTo_picker').datetimepicker('getDate')).unix(),
                utcOffset: mfrom.utcOffset()
            };
            var error = false;
            if (newPlace) {
                data.place = $("#txtPlaceNew").val();
                data.newPlace = true;
                if (markers.length >= 1) {
                    data["lng"] = markers[0].position.lng();
                    data["lat"] = markers[0].position.lat();
                } else {
                    checkForm("#txtPlace", "Bitte Ort auf der Karte auswählen");
                    error = true;
                }
            } else {
                data.place = $("#txtPlace").val();
                data.newPlace = false;
                if (data.place === undefined || data.place === "") {
                    checkForm("#txtPlace");
                    error = true;
                }
            }
            if (data.title === undefined || data.title === "") {
                checkForm("#txtTitle");
                error = true;
            }

            if (data.desc === undefined || data.desc === "") {
                $("#divDescHelp").html("Die Beschreibung darf nicht leer sein");
                if (!$("#divDesc").hasClass(errorState)) {
                    $("#divDesc").addClass(errorState);
                }
                error = true;
            }
            if (data.to < data.from) {
                $("#divToHelp").html('Das "Bis"-Datum darf nicht kleiner sein als das "Von"-Datum');
                if (!$("#divDate").hasClass(errorState)) {
                    $("#divDate").addClass(errorState);
                }
            }
            if (!error) {
                club_ajax_post("calendar_save_event", data, function (resp) {
                    alert(JSON.stringify(resp));
                });
            }
        });
        var fromDiv = $('#dateFrom_picker');
        var toDiv = $('#dateTo_picker');
        fromDiv.datetimepicker({
            altField: "#dateFrom",
            altFieldTimeOnly: false,
            stepMinute: 15,
            dateFormat: 'dd.mm.yy',
            timeOnlyTitle: 'Zeit wählen',
            timeText: 'Zeit',
            hourText: 'Stunde',
            minuteText: 'Minute',
            secondText: 'Sekunde',
            millisecText: 'Millisekunde',
            microsecText: 'Mikrosekunde',
            timezoneText: 'Zeitzone',
            currentText: 'Jetzt',
            closeText: 'Fertig',
            timeFormat: 'HH:mm',
            timeSuffix: '',
            amNames: ['vorm.', 'AM', 'A'],
            pmNames: ['nachm.', 'PM', 'P'],
            isRTL: false,
            prevText: '&#x3C;Zurück',
            nextText: 'Vor&#x3E;',
            monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            monthNamesShort: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
                'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
            dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
            dayNamesShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
            dayNamesMin: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
            weekHeader: 'KW',
            firstDay: 1,
            showMonthAfterYear: false,
            yearSuffix: '',
            onSelect: function () {
                var date = moment(fromDiv.datetimepicker('getDate'));
                var unixFrom = date.unix();
                var unixTo = moment(toDiv.datetimepicker('getDate')).unix();
                if (unixTo < unixFrom) {
                    date.add(8, 'h');
                    toDiv.datetimepicker('setDate', date.toDate());
                }
                toDiv.datetimepicker('option', 'minDate', fromDiv.datetimepicker('getDate'));
            }
        });

        toDiv.datetimepicker({
            altField: "#dateTo",
            altFieldTimeOnly: false,
            stepMinute: 15,
            dateFormat: 'dd.mm.yy',
            timeOnlyTitle: 'Zeit wählen',
            timeText: 'Zeit',
            hourText: 'Stunde',
            minuteText: 'Minute',
            secondText: 'Sekunde',
            millisecText: 'Millisekunde',
            microsecText: 'Mikrosekunde',
            timezoneText: 'Zeitzone',
            currentText: 'Jetzt',
            closeText: 'Fertig',
            timeFormat: 'HH:mm',
            timeSuffix: '',
            amNames: ['vorm.', 'AM', 'A'],
            pmNames: ['nachm.', 'PM', 'P'],
            isRTL: false,
            prevText: '&#x3C;Zurück',
            nextText: 'Vor&#x3E;',
            monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
                'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            monthNamesShort: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun',
                'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
            dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'],
            dayNamesShort: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
            dayNamesMin: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
            weekHeader: 'KW',
            firstDay: 1,
            showMonthAfterYear: false,
            yearSuffix: ''
        });

        fromDiv.datetimepicker('setDate', moment.unix(from).toDate());
        toDiv.datetimepicker('setDate', moment.unix(to).toDate());

        toDiv.datetimepicker('option', 'minDate', fromDiv.datetimepicker('getDate'));
        $('.combobox').combobox({bsVersion: '3'});

        $("#divMap").hide();

        $("#btnNewPlace").on("click", function () {
            if (newPlace) {
                $("#txtPlaceNew").hide();
                $('#txtPlaceChoose').show();
                $("#divMap").hide();
                $("#btnNewPlace").html("Neuer Ort");
                newPlace = false;
            } else {
                initMap();
                $("#txtPlaceNew").show();
                $('#txtPlaceChoose').hide();
                $("#divMap").show();
                $("#btnNewPlace").html("Ort auswählen");
                newPlace = true;
            }
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
        map.addListener('click', function (e) {
            var latLng = e.latLng;
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];
            markers.push(new google.maps.Marker({
                title: "Treffpunkt",
                position: latLng,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP
            }));
            useMarker = true;

        });

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

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
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
                $("#txtPlaceNew").val(place.name);
                useMarker = true;
            });
            map.fitBounds(bounds);
        });

    }

</script>

