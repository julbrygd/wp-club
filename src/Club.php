<?php

namespace Club;

/**
 * Description of Club
 *
 * @author stephan
 */
class Club {

    private static $INSTANCE = NULL;
    private $parrent_page;
    private $plugin_url;
    private $modules;

    private function __construct() {
        $this->parrent_page = "club/admin/index.php";
        $this->plugin_url = plugin_dir_url(__FILE__) . '/../../';
        $this->modules = array();
    }

    public function add_menu($title, $caps, $slug) {
        add_submenu_page($this->parrent_page, $title, $title, $caps, $slug);
    }

    public function createMenu() {
        add_menu_page('Club', 'Club', 'club_member', $this->parrent_page, null, 'dashicons-id-alt');
        $this->add_menu("Club Roles", 'club_admin', "club/admin/roles.php");
    }

    public function add_scripts() {
        wp_register_style('bootstrap-admin', $this->plugin_url . "/css/bootstrap-admin.css");
        wp_register_script("bootstrap-adminjs", $this->plugin_url . "/js/bootstrap.min.js", array('jquery'));
        wp_enqueue_script('ajax-script', $this->plugin_url . "/js/wp-ajax.js", array('jquery'));
        wp_localize_script('ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_enqueue_script('ajax-script');
        wp_enqueue_style('bootstrap-admin');
        wp_enqueue_script('bootstrap-adminjs');
    }

    public function init() {
        $this->loadModules();
    }

    public function loadModules() {
        foreach (get_option("club_modules") as $key => $class) {
            if (!array_key_exists($key, $this->modules)) {
                write_log("Create class " . $class);
                $this->modules[$key] = new $class;
                $this->modules[$key]->setClub($this);
                $this->modules[$key]->init();
            }
        }
    }

    public function registerAdminAjax($handle, $function) {
        add_action('wp_ajax_club_' . $handle, $function);
    }

    /**
     * Get the singleton instanche of the plugin class
     * 
     * @return type Club\Club
     */
    public static function getInstance() {
        if (Club::$INSTANCE == NULL) {
            Club::$INSTANCE = new Club();
        }
        return Club::$INSTANCE;
    }

    /**
     * Run function starts the plugin
     */
    public static function run() {
        $club = Club::getInstance();
        add_action('admin_init', array(&$club, 'init'));
        add_action('admin_menu', array(&$club, 'createMenu'));
        add_action('admin_enqueue_scripts', array(&$club, 'add_scripts'));
    }

    public function getCaps() {
        return get_option('club_caps');
    }

    public static function install() {

        global $wp_roles;

        add_option('club_caps', array('club_admin', 'club_member'));
        $admin = $wp_roles->get_role('administrator');
        $admin->add_cap('club_admin');
        $admin->add_cap('club_member');
        $wp_roles->add_role('club_admin', 'Club Administrator');
        $club_admin = get_role('club_admin');
        foreach ($admin->capabilities as $cap => $has) {
            if ($has == 1) {
                $club_admin->add_cap($cap);
            }
        }
        $wp_roles->add_role('club_member', 'Club Mitglied');
        $club_member = get_role('club_member');
        $auhor = $wp_roles->get_role('author');
        $club_member->add_cap('club_member');
        foreach ($auhor->capabilities as $cap => $has) {
            if ($has == 1) {
                $club_member->add_cap($cap);
            }
        }
        add_option("club_modules", array(
            "role" => '\Club\Admin\Roles'
        ));
    }

    public static function uninstall() {
        global $wp_roles;
        $caps = get_option('club_caps');
        foreach ($wp_roles->get_names() as $name => $short) {
            $role = $wp_roles->get_role($name);
            foreach ($caps as $cap) {
                if ($role->has_cap($cap)) {
                    $role->remove_cap($cap);
                }
            }
            if (substr($name, 0, 5) === 'club_') {
                $wp_roles->remove_role($name);
            }
        }
        delete_option('club_caps');
        delete_option("club_modules");
    }

}
