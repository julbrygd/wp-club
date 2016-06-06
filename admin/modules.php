<?php
$club = \Club\Club::getInstance();
$module = $club->getModules()->getModules();
$activ = $club->getModules()->getActivated();
?>

<div class="bootstrap-wrapper">
    <div class="container">
        <h1>Club Module</h1>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Modul</th>
                    <th>Beschreibung</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($module as $mod) { ?>
                    <tr>
                        <td><?php echo $mod->getName(); ?></td>
                        <td><?php echo $mod->getDescription(); ?></td>
                        <td>
                            <?php if (!$club->getModules()->isProtected($mod->getName())) { ?>
                            <?php if (in_array($mod->getName(), $activ)) { ?>
                                <button data-name="<?php echo $mod->getName(); ?>" class="btn btn-default deactivate">Deaktivieren</button>
                            <?php } else { ?>
                                <button data-name="<?php echo $mod->getName(); ?>" class="btn btn-default activate">Aktivieren</button>
                            <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button class="btn btn-default" id="btnReload">Neu Laden</button>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        $j = jQuery.noConflict();

        function toggleModule(name, action) {
            var data = {
                'module': name,
                'do': action
            };
            club_ajax_post("toggle_module", {'modules': data}, function (response) {
                if (response === "ok") {
                    location.reload();
                }
            });
        }

        $j(".activate").on("click", function (event) {
            event.preventDefault();
            toggleModule($j(this).data("name"), 'enable');
        });

        $j(".deactivate").on("click", function (event) {
            event.preventDefault();
            toggleModule($j(this).data("name"), 'disable');
        });
        
        $j("#btnReload").on("click", function () {
            club_ajax_post("relaod_modules", {}, function (resp) {
                location.reload();
            });
        });
    });
</script>

