<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$listTemplates = $this->getModuleInstance('list')->getListClasses();
?>

<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Liste</h1>
        <form class="form-horizontal">
            <div class="form-group" id="divName" class="can-have-error">
                <label for="txtName" class="col-sm-2 control-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control can-have-error" value="" id="txtName" placeholder="Name" data-divid="divName" />
                    <span id="divNameHelp" class="help-block"></span>
                </div>
            </div>
            <div class="form-group" id="divType" class="can-have-error">
                <label for="selType" class="col-sm-2 control-label">Typ</label>
                <div class="col-sm-10">
                    <select class="form-control can-have-error" id="selType"  data-divid="divType">
                        <option value="-1">Bitte Typ auswählen ...</option>
                        <?php foreach ($listTemplates as $tmpl) { ?>
                            <option value="<?php echo $tmpl["class"] ?>"><?php echo $tmpl["name"] . " (" . $tmpl["description"] . ")" ?></option>
                        <?php } ?>
                    </select>
                    <span id="divTypeHelp" class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-default" id="btnSave" type="button">Speichern</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {

        var $ = jQuery;
        $("#btnSave").on('click', function () {
            $("#divType").removeClass("has-error");
            $("#divTypeHelp").html("");
            $("#divName").removeClass("has-error");
            $("#divNameHelp").html("");
            var name = $("#txtName").val();
            var clazz = $("#selType").val();
            var ok = true;
            if (name === "") {
                ok = false;
                $("#divName").addClass("has-error");
                $("#divNameHelp").html("Bitte einen Namen eingeben");
                $("#txtName").on('change', function () {
                    var name = $("#txtName").val();
                    if (name !== "" && name.length >= 1) {
                        $("#divName").removeClass("has-error");
                        $("#divNameHelp").html("");
                    }
                });
            }
            if (clazz === "-1") {
                ok = false;
                $("#divType").addClass("has-error");
                $("#divTypeHelp").html("Bitte einen Typ auswählen");
                $("#selType").on('change', function () {
                    var id = $("#selType").val();
                    if (id !== "-1") {
                        $("#divType").removeClass("has-error");
                        $("#divTypeHelp").html("");
                    }
                });
            }
            //alert(JSON.stringify({"name": name, "class": clazz}));
            if (ok) {
                club_ajax_post("list_new", {"name": name, "class": clazz}, function (resp) {
                    var data = JSON && JSON.parse(resp) || $.parseJSON(resp);
                    if(data.status === "ok"){
                        location.href = "<?php echo wp_nonce_url(admin_url('admin.php?page=club_list')) ?>";
                    }
                });
            }
        });
    });
</script>