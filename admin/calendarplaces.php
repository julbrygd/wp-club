<?php
$places = \Club\Admin\Calendar\Place::getAll();
?>
<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Termine Orte</h1>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Latitude</td>
                    <td>Longitude</td>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($places as $place) { ?>
                    <tr id="<?php echo $place->getUuid() ?>">
                        <td name="name"><?php echo $place->getName() ?></td>
                        <td><?php echo $place->getLat() ?></td>
                        <td><?php echo $place->getLng() ?></td>
                        <td>
                            <button class="btn btn-default btn-sm btnDelete" data-toggle="tooltip" data-placement="top" title="Löschen" data-uuid="<?php echo $place->getUuid() ?>">
                                <span class="glyphicon glyphicon-trash" />
                            </button>
                        </td>
                    </tr>
                <?php } ?>    
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $ = jQuery.noConflict();
    var nonces = {
<?php foreach ($places as $place) { ?>"<?php echo $place->getUuid() ?>": "<?php echo wp_create_nonce("club-delete-place-" . $place->getUuid()) ?>",
<?php } ?>
    };
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(".btnDelete").on('click', function() {
        var uuid = $(this).data("uuid");
        var name = $("tr#" + uuid + " td[name=name]").html();
        if (confirm("Sind sie sicher, dass sie den Ort " + name + " löschen wollen?")){

            club_ajax_post("calendar_delete_place", {"uuid": uuid, "nonce": nonces[uuid]}, function (resp) {
                if(resp === "1") {
                    location.reload();
                } else {
                    alert(resp);
                }
            });
         }
        $(this).tooltip('hide');
        });
    });
</script>