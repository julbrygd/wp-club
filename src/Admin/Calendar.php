<?php

namespace Club\Admin;

use \Club\Admin\Module;

class Calendar extends Module {

    private $API_KEY;
    private $_club;
    private $maps_api_loaded;
    private static $FORMATS = array(
        "de" => array(
            "php" => "U",
            "moment" => "DD.MM.YYYY HH:MM"
        )
    );

    public function init() {
        $this->API_KEY = get_option("google_maps_api_key");
        $this->install();
        $this->maps_api_loaded = false;
        $this->_club->registerAdminAjax('calendar_save_event', array(&$this, 'saveEvent'));
    }

    public function setClub($club) {
        $this->_club = $club;
    }

    public function addMenu() {
        $this->_club->add_menu("Termine", "club_admin", "club/admin/calendar.php");
        $this->_club->add_menu("Termine Form", "club_admin", "club/admin/calendarform.php", true);
    }

    public function install() {
        
    }

    public function urlHandler() {
        
    }

    public function public_init() {
        $this->urlHandler();
    }

    private function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    public function add_scripts() {
        parent::add_scripts();
        $site = get_current_screen();
        if ($this->endsWith($site->base, "calendarform")) {
            wp_enqueue_script("mommentjs");
            wp_register_script("jquery-ui-sliderAccess", $this->_club->plugin_url . "/js/jquery-ui-sliderAccess.js", array("jquery-ui-core", "jquery-effects-slide"));
            wp_register_script("jquery-ui-timepicker-addon", $this->_club->plugin_url . "/js/jquery-ui-timepicker-addon.js", array("jquery-ui-core", "jquery-ui-datepicker", "jquery-ui-sliderAccess"));
            wp_register_script("jquery-ui-timepicker-addon-i18n", $this->_club->plugin_url . "/js/i18n/jquery-ui-timepicker-addon-i18n.min.js", array("jquery-ui-timepicker-addon"));
            wp_register_style("jquery-ui-timepicker-addon-css", $this->_club->plugin_url . "/css/jquery-ui-timepicker-addon.min.css");
            wp_enqueue_script("jquery-ui-sliderAccess");
            wp_enqueue_script("jquery-ui-timepicker-addon");
            wp_enqueue_style("jquery-ui-timepicker-addon-css");
            $maps_api = get_option('google_maps_api_key', '');
            if ($maps_api != '') {
                wp_enqueue_script(
                        'google-maps', '//maps.googleapis.com/maps/api/js?key=' . $maps_api . '&libraries=places', array(), '1.0', true
                );
                $this->maps_api_loaded = TRUE;
            }
        }
    }
    
    public function isMapsLoaded(){
        return $this->maps_api_loaded;
    }
    
    public function getDateFormat($type = "php") {
        $module = \Club\Club::getInstance()->getModules()->getModule("calendar");
        $default = "de";
        if($module != NULL) {
            foreach ($module->getSettings() as $setting){
                if($setting["key"] == "calender_time_format"){
                    $default = $setting["default"];
                }
            }
        }
        $formatString = get_option("calender_time_format", $default);
        return self::$FORMATS[$formatString][$type];
    }
    
    public function saveEvent() {
        if (array_key_exists("action", $_POST) && $_POST['action'] == 'club_calendar_save_event') {
            $id = null;
            if(array_key_exists("uuid", $_POST)){
                $id = $_POST["$id"];
            }
            $event = new \Club\Admin\Calendar\Event($id);
            $event->fromPost($_POST, $this->getDateFormat());
            $place = null;
            if(array_key_exists("placeUuid", $_POST)){
                $place = new \Club\Admin\Calendar\Place($_POST["placeUuid"]);
            } else {
                 $place = new \Club\Admin\Calendar\Place();
            }
            $place->fromPost($_POST);
            $event->setPlace($place);
            echo json_encode($event);
        }
        wp_die();
    }

}
