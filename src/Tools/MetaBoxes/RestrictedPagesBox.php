<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Tools\MetaBoxes;

/**
 * Description of RestrictedPages
 *
 * @author stephan
 */
class RestrictedPagesBox implements MetaBoxesInterface {

    private $meta_key;

    public function __construct() {
        $this->meta_key = self::getKeyAllowedRoles();
    }
    
    public static function getKeyAllowedRoles(){
        return 'club_restricted_allowed_roles';
    }

    public function form($post) {
        wp_nonce_field(basename(__FILE__), 'club_restricted_box_none');
        $roles = wp_roles()->get_names();
        $existing = get_post_meta($post->ID, $this->meta_key, true);
        if(!is_array($existing)){
            $existing = array();
        }
        ?>
        <p>
        <legend><?php _e("Berechtigte Rollen", 'club'); ?>:</legend>
        <?php foreach ($roles as $key => $name) { 
            $checked = "";
            if(in_array($key, $existing)){
                $checked=' checked="checked"';
            }
            ?>
            <label for="postimagediv-hide">
                <input class="hide-postbox-tog club_alowed_role" name="club_alowed_role_<?php echo $key; ?>" type="checkbox" value="<?php echo $key; ?>"<?php echo $checked;?> /><?php echo $name; ?>
            </label><br />
        <?php } ?>
            <a class="buttonm club_alowed_role_select_all">Alle Ausw&auml;hlen</a> <a class="button club_alowed_role_unselect_all">Alle Abw&auml;hlen</a><br />
            Wenn keine Rolle ausgew&auml;t ist, sind alle Berechtigt.
        </p>
        <?php
    }

    public function getName() {
        return "club_restricted_pages";
    }

    public function register() {
        add_meta_box(
                $this->getName(), "Benutzer Restricktion", array(&$this, "form"), "page", "side"
        );
    }

    public function save($post_id, $post) {
        if (!isset($_POST['club_restricted_box_none']) || !wp_verify_nonce($_POST['club_restricted_box_none'], basename(__FILE__)))
            return $post_id;

        $roles = array();
        foreach ($_POST as $key => $val) {
            $length = strlen("club_alowed_role_");
            if (substr($key, 0, $length) === "club_alowed_role_") {
                $roles[] = $val;
            }
        }


        $old_roles = get_post_meta($post_id, $this->meta_key, true);


        $add = false;
        $update = false;
        $delete = false;
        if (is_array($old_roles)) {
            $update = true;
        } else if (count($roles) > 0) {
            $add = true;
        } else if (count($roles) == 0 && is_array($old_roles)) {
            $delete = true;
        }

        if ($add) {
            add_post_meta($post_id, $this->meta_key, $roles, true);
        } elseif ($update) {
            update_post_meta($post_id, $this->meta_key, $roles);
        } elseif ($delete) {
            delete_post_meta($post_id, $this->meta_key, $old_roles);
        }
    }

}
