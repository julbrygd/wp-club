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
                    <th>&nbsp;</th>
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
                            <td><input id="<?php echo $id; ?>" type="checkbox"<?php echo $opts; ?>/></td>
                        <?php } ?>
                        <?php if (substr($key, 0, 5) === "club_" && $key != "club_admin" && $key != "club_member") { ?>
                            <td>
                                <a href="#" class="delete_role" data-key="<?php echo $key; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                            </td>
                        <?php } else { ?>
                            <td>&nbsp;</td>
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
                        <form>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="displayName">Anzeige Name</label>
                                <input type="text" class="form-control" id="displayName" placeholder="Anzeige Name">
                            </div>
                            <div class="form-group">
                                <label for="parrent">Erbt von</label>
                                <select class="form-control" id="parrent">
                                    <?php
                                    foreach ($roles as $key => $role) {
                                        ?>
                                        <option value="<?php echo $key; ?>"><?php echo translate_user_role($role["name"]); ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
                        <button type="button" class="btn btn-primary" id="btnSaveNew">Speichern</button>
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
            club_ajax_post("save_all_role", {'roles': data}, function (response) {
                if (response === "ok") {
                    location.reload();
                }
            });
        });

        $j("#btnNew").on("click", function () {
            $j('#newRole').modal('show');
        });

        $j("#btnSaveNew").on("click", function () {
            var data = {
                'name': $j("#name").val(),
                'displayName': $j("#displayName").val(),
                'parrent': $j("#parrent").val()
            };
            if (data.name === "" || data.displayName === "") {
                alert("Bitte alle Felder ausfüllen");
                return;
            }
            club_ajax_post("save_new_role", data, function (response) {
                if (response === "ok") {
                    location.reload();
                } else {
                    alert(response);
                }
            });
        });
        $j(".delete_role").on("click", function () {
            var key = $j(this).data("key");
            var conf = confirm("Sind sie sicher, dass sie die Rolle " + key + " löschen wollen?");
            if (conf) {
                club_ajax_post("delete_role", {'key': key}, function (response) {
                    if (response === "ok") {
                        location.reload();
                    } else {
                        alert(response);
                    }
                });
            }
        });

    });
</script>