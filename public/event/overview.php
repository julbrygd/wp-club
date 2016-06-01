<div class="bootstrap-wrapper">
    <div class="container-no-padding container">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Titel</th>
                    <th>Ort</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach(\Club\Admin\Calendar\Event::getAll(new \DateTime()) as $event) { ?>
                <tr>
                    <td><?php echo $event->getFromFormated(). " - " . $event->getToFormated();?></td>
                    <td><?php echo $event->getTitle()?></td>
                    <td><?php echo $event->getPlace()->getName()?></td>
                    <td><a href="<?php echo get_permalink($event->getPostId());?>">Details</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var $ = jQuery.noConflict();
    $(document).ready(function () {
        function resize() {
            var width = $("h1.entry-title").width();
            $(".container").width(width);
        }
        resize();
        $(window).resize(resize);
    });
</script>