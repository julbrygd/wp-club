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
        <?php        switch ($setting["type"]) {
            case "text": ?>
        <td><input type="text" name="<?php echo $setting["key"] ?>" size="50" value="<?php echo esc_attr(get_option($setting["key"], $default) ); ?>" /></td>
        <?php break;
            case "select": ?>
        <td>
            <select name="<?php echo $setting["key"] ?>">
                <option value="-1">Bitte auswählen</option>
                <?php $current = get_option($setting["key"], $default);
                foreach($setting["elements"] as $key=>$text) { 
                    $selected="";
                    if($current == $key) {
                        $selected = " selected=\"selected\"";
                    }
                    ?>
                <option value="<?php echo esc_attr($key) ?>"<?php echo $selected ?>><?php echo esc_attr($text) ?></option>
                <?php } ?>
            </select>
        </td>
        <?php break; 
        }?>
        </tr>
        <?php } ?>
    </table>
<?php submit_button(); ?>
</form>
</div>