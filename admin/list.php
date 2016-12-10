<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$dao = \Club\Club::getInstance()->getDao("list");

$lists = $dao->findAll();
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
<?php    foreach ($lists as $key=>$list) { 
    $c = $list->getClass();
    $type = $c::getName();
    ?>
                <tr>
                    <td><?php echo $list->getDisplayName() ?></td>
                    <td><?php echo $type ?></td>
                    <td></td>
                </tr>
<?php } ?>
            </tbody>
        </table>
        <div>
            <a href="<?php print wp_nonce_url(admin_url('admin.php?page=club_list&view=edit')); ?>"
               class="button button-primary">Neu</a>&nbsp;
        </div>
    </div>
</div>