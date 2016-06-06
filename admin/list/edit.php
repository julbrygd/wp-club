<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$types = Club\ListModule\Types::getInstance();

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
                        <?php foreach ($types->getTypes() as $type) { ?>
                        <option value="<?php echo get_class($type)?>"><?php echo $type->getName() ?></option>
                        <?php } ?>
                    </select>
                    <span id="divTypeHelp" class="help-block"></span>
                </div>
            </div>
        </form>
    </div>
</div>