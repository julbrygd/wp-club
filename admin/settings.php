<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$modules = \Club\Club::getInstance()->getModules();
?>

<div class="wrap">
<h2>Club Plugin Einstellugne</h2>
<form method="post" action="options.php"> 
    <?php settings_fields( 'club-settings' ); ?>
    <?php do_settings_sections( 'club-settings' ); ?>
    <table class="form-table">
        <?php foreach ($modules->getSettings() as $setting) { 
            $default = false;
            if(array_key_exists("default", $setting)){
                $default = $setting["default"];
            }
        ?>
        <tr valign="top">
        <th scope="row"><?php echo $setting["displayName"] ?></th>
        <td><input type="text" name="<?php echo $setting["key"] ?>" size="50" value="<?php echo esc_attr(get_option($setting["key"], $default) ); ?>" /></td>
        </tr>
        <?php } ?>
    </table>
<?php submit_button(); ?>
</form>
</div>