<?php
$event = \Club\Admin\Calendar\Event::findById($atts['event_id']);
?>
<div class="bootstrap-wrapper">
    <div class="container-no-padding container">
        <div class="row">
            <div class="col-xs-2">
                <strong>Wann:</strong>
            </div>
            <div class="col-xs-10">
                <?php echo $event->getFromFormated() ?> bis <?php echo $event->getToFormated() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <strong>Ort:</strong>
            </div>
            <div class="col-xs-10">
                <?php echo $event->getPlace()->getName() ?>

            </div>
        </div>
        <?php if ($this->isMapsLoaded()) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="map_container">
                        <iframe class="map" 
                                frameborder="0" 
                                style="border:0" 
                                src="https://www.google.com/maps/embed/v1/place?q=<?php echo $event->getPlace()->getLat() ?>%2C<?php echo $event->getPlace()->getLng() ?>&key=<?php echo $this->API_KEY?>" 
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-12">
                <strong>Beschreibung:</strong>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php echo $event->getDescripion() ?>
            </div>
        </div>
    </div>
</div>

<?php if ($this->isMapsLoaded()) { ?>
    <style type="text/css">
        .map_container {
            margin-bottom: 10px;
        }
    </style>
    <script type="text/javascript">
        var lat = <?php echo $event->getPlace()->getLat() ?>;
        var lng = <?php echo $event->getPlace()->getLng() ?>;
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: 16,
                mapTypeId: google.maps.MapTypeId.HYBRID
            });
            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map,
                title: "Treffpunkt"
            });
        }
        var $ = jQuery.noConflict();
        $(document).ready(function () {

            function resizeMap() {
                var width = $("h1.entry-title").width();
                var height = (width / 16.0) * 9;
                $(".map").width(width).height(height);
                $(".map_container").width(width).height(height);
            }
            resizeMap()
            $(window).resize(resizeMap);
        });
    </script>
<?php } ?>
