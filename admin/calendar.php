<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Club\Admin\Calendar\Event;

global $wpdb;
//var_dump(get_categories());
$events = Event::getAll();
?>

<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Termine</h1>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Von</th>
                    <th>Bis</th>
                    <th>Artikel</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event) { ?>
                <tr>
                    <td class="<?php echo $event->getUuid()?>" name="name"><?php echo $event->getTitle() ?></td>
                    <td><?php echo $event->getFromFormated() ?></td>
                    <td><?php echo $event->getToFormated() ?></td>
                    <td><a href="<?php echo get_permalink($event->getPostId());?>" target="_blank">Artikel</a></td>
                    <td>
                        <a class="btn btn-default btn-sm btnEdit" href="<?php print wp_nonce_url(admin_url('admin.php?page=club_events&view=form&uuid='.$event->getUuid())); ?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>&nbsp;
                        <button class="btn btn-default btn-sm btnDelete" data-uuid="<?php echo $event->getUuid() ?>">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div>
            <a href="<?php print wp_nonce_url(admin_url('admin.php?page=club_events&view=form')); ?>"
               class="button button-primary">Neu</a>&nbsp;
               <a href="<?php print wp_nonce_url(admin_url('admin.php?page=club_events&view=places')); ?>"
               class="button button-primary">Orte Bearbeiten</a>
        </div>
    </div>

    
</div>



<script type="text/javascript">
    jQuery(document).ready(function () {
        $ = jQuery.noConflict();
        var nonces = {
<?php foreach ($events as $event) { ?>"<?php echo $event->getUuid() ?>": "<?php echo wp_create_nonce("club-delete-event-" . $event->getUuid()) ?>",
<?php } ?>
    };
        $('.btnDelete').on('click', function () {
            var uuid = $(this).data("uuid");
            var name = $("."+uuid+"[name=name]").html();
            if(confirm("Sind sie sicher, dass sie den Termin \""+name+"\" wirklich löschen?")){
                club_ajax_post("calendar_delete_evnet", {"uuid": uuid, "nonce": nonces[uuid]}, function (resp) {
                if(resp === "1") {
                    location.reload();
                } else {
                    alert(resp);
                }
            });
            }
        });
    });
</script>