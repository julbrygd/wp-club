<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $wpdb;
//var_dump(get_categories());
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

            </tbody>
        </table>
        <div>
            <a href="<?php print wp_nonce_url(admin_url('admin.php?page=club/admin/calendarform.php')); ?>"
               class="button button-primary">Neu</a>
        </div>
    </div>

    
</div>



<script type="text/javascript">
    jQuery(document).ready(function () {
        $ = jQuery.noConflict();

        $('#txtForm').on('', function () {

        });
    });
</script>