<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$string = new Club\ListModule\Types\String("testField", "Test Field", "testList")
?>
<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Listen Verwalten</h1>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Namen</th>
                    <th>Type</th>
                    <th>&nbsp;</
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        <div>
            <a href="<?php print wp_nonce_url(admin_url('admin.php?page=club_list&view=edit')); ?>"
               class="button button-primary">Neu</a>&nbsp;
        </div>
    </div>
    <?php echo $string->getCreateStatement() ?>
</div>