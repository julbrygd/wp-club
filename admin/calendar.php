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
            <button class="btn btn-default" id="btnNew" data-toggle="modal" data-target="#mdlForm">Neuer Termin</button>
        </div>
    </div>

    <div class="modal fade" id="mdlForm" tabindex="-1" role="dialog" aria-labelledby="mdlFormLable">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Termin</h4>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                    <button type="button" class="btn btn-primary" id="btnSave">Speichern</button>
                </div>
            </div>
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