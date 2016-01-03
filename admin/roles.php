<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$roles = get_editable_roles();
$caps = Club\Club::getInstance()->getCaps();
$not_editable = array('administrator', 'club_admin');
?>
<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Rollen</h1>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Rolle</th>
                    <?php foreach ($caps as $cap) { ?>
                        <th><?php echo $cap ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($roles as $key => $role) {
                    $editable = in_array($key, $not_editable);
                    $disabled = "";
                    if ($editable) {
                        $disabled = ' disabled="disabled"';
                    }
                    ?>
                    <tr>
                        <td><?php echo translate_user_role($role["name"]); ?></td>
                        <?php
                        foreach ($caps as $cap) {
                            $checked = "";
                            if (array_key_exists($cap, $role["capabilities"])) {
                                $checked = 'checked="checked"';
                            }
                            $opts = $disabled . $checked;
                            $id = $key . ";" . $cap;
                            ?>
                            <th><input id="<?php echo $id; ?>" type="checkbox"<?php echo $opts; ?>/></th>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button class="btn" id="btnSave">Speichern</button>
        <button class="btn" id="btnNew">Neue Rolle</button>
        <div class="modal fade" id="newRole" tabindex="-1" role="dialog" aria-labelledby="newRoleLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Neue Rolle</h4>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                        <button type="button" class="btn btn-primary">Speichern</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $j = jQuery.noConflict();
        $j('#btnSave').on('click', function () {
            var data = {};
            $j('input').each(function () {
                var id = $j(this).attr('id').split(';');
                var role = id[0];
                var cap = id[1];
                var checked = $j(this).prop("checked");
                if (data[role] === undefined) {
                    data[role] = {};
                }
                data[role][cap] = checked;
            });
            club_ajax_post("save_all", {'roles': data}, function(response){
                if(response === "ok"){
                    location.reload();
                }
            });
        });
        
        $j("#btnNew").on("click", function (){
            $j('#newRole').modal('show');
        });
    });
</script>