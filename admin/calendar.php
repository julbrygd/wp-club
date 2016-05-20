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
if (!session_id()) {
    session_start();
}
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
                    <th>Kategorie</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event) { ?>
                <tr>
                    <td><?php echo $event->getTitle() ?></td>
                    <td><?php echo $event->getFromFormated() ?></td>
                    <td><?php echo $event->getToFormated() ?></td>
                    <td>&nbsp;</td>
                    <td>
                        <a class="btn btn-default btn-sm btnEdit" href="<?php print wp_nonce_url(admin_url('admin.php?page=club/admin/calendarform.php')); ?>" data-uuid="<?php $event->getUuid() ?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>&nbsp;
                        <button class="btn btn-default btn-sm btnDelete" data-uuid="<?php $event->getUuid() ?>">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div>
            <a href="<?php print wp_nonce_url(admin_url('admin.php?page=club/admin/calendarform.php')); ?>"
               class="button button-primary">Neu</a>&nbsp;
               <a href="<?php print wp_nonce_url(admin_url('admin.php?page=club/admin/calendarplaces.php')); ?>"
               class="button button-primary">Orte Bearbeiten</a>
        </div>
    </div>

    
</div>



<script type="text/javascript">
    jQuery(document).ready(function () {
        $ = jQuery.noConflict();

        $('.btnEdit').on('click', function () {
            alert($(this).data("uuid"));
        });
    });
</script>