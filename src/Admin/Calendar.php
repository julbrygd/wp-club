<?php

namespace Club\Admin;

use \Club\Admin\Module;
use \Club\Modules\Page;

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
        $this->_club->registerAdminAjax('calendar_delete_place', array(&$this, 'deletePlace'));
        $this->_club->registerAdminAjax('calendar_delete_evnet', array(&$this, 'deleteEvent'));
    }

    public function registerPages() {
        $this->registerPage(new Page("club_events", "", null, $this, "admin/calendar.php"));
        $this->registerPage(new Page("club_events", "form", null, $this, "admin/calendarform.php"));
        $this->registerPage(new Page("club_events", "places", null, $this, "admin/calendarplaces.php"));
    }

    public function setClub($club) {
        $this->_club = $club;
    }

    public function install() {
        \Club\Admin\Calendar\Db::execute();
    }

    public function urlHandler() {
        
    }

    public function public_init() {
        add_filter("previous_post_link", array(&$this, "linkFilter"));
        add_filter("next_post_link", array(&$this, "linkFilter"));
        add_shortcode('club_event', array(&$this, 'eventShortCode'));
        $this->urlHandler();
    }

    private function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    public function add_scripts() {
        parent::add_scripts();
        $page = filter_input(INPUT_GET, "page") == null ? "" : filter_input(INPUT_GET, "page");
        $view = filter_input(INPUT_GET, "view") == null ? "" : filter_input(INPUT_GET, "view");
        if ($page == "club_events" && $view == "form") {
            wp_enqueue_script("mommentjs");
            wp_register_script("jquery-ui-sliderAccess", $this->_club->plugin_url . "/js/jquery-ui-sliderAccess.js", array("jquery-ui-core", "jquery-effects-slide"));
            wp_register_script("jquery-ui-timepicker-addon", $this->_club->plugin_url . "/js/jquery-ui-timepicker-addon.js", array("jquery-ui-core", "jquery-ui-datepicker", "jquery-ui-sliderAccess"));
            wp_register_script("jquery-ui-timepicker-addon-i18n", $this->_club->plugin_url . "/js/i18n/jquery-ui-timepicker-addon-i18n.min.js", array("jquery-ui-timepicker-addon"));
            wp_register_style("jquery-ui-timepicker-addon-css", $this->_club->plugin_url . "/css/jquery-ui-timepicker-addon.min.css");
            wp_register_script("bootstrap-combobox.js", $this->_club->plugin_url . "/js/bootstrap-combobox.js", array("bootstrap-adminjs"));
            wp_register_style("bootstrap-combobox.css", $this->_club->plugin_url . "/css/bootstrap-combobox.css");

            wp_enqueue_script("jquery-ui-sliderAccess");
            wp_enqueue_script("jquery-ui-timepicker-addon");
            wp_enqueue_style("jquery-ui-timepicker-addon-css");
            wp_enqueue_script("bootstrap-combobox.js");
            wp_enqueue_style("bootstrap-combobox.css");
            $this->loadGoogleMaps();
        }
    }

    public function isMapsLoaded() {
        return $this->maps_api_loaded;
    }

    public function getDateFormat($type = "php") {
        $module = \Club\Club::getInstance()->getModules()->getModule("calendar");
        $default = "de";
        if ($module != NULL) {
            foreach ($module->getSettings() as $setting) {
                if ($setting["key"] == "calender_time_format") {
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
            $event = null;
            if (array_key_exists("uuid", $_POST)) {
                $id = $_POST["uuid"];
                $event = \Club\Admin\Calendar\Event::findById($id);
            } else {
                $event = new \Club\Admin\Calendar\Event($id);
            }

            $event->fromPost($_POST, $this->getDateFormat());
            $place = null;
            $newPlace = array_key_exists("newPlace", $_POST) ? filter_var(strtolower($_POST["newPlace"]), FILTER_VALIDATE_BOOLEAN) : false;
            if (!$newPlace) {
                $id = $_POST["place"];
                $place = \Club\Admin\Calendar\Place::findById($id);
            } else {
                $place = new \Club\Admin\Calendar\Place();
                $place->setName($_POST["place"]);
                $place->setLat($_POST["lat"]);
                $place->setLng($_POST["lng"]);
                $place->save(false);
            }
            $event->setPlace($place);
            $shortCode = "[club_event event_id=" . $event->getUuid() . " type=event]";
            $postId = wp_insert_post(array(
                "ID" => $event->getPostId(),
                "post_title" => $event->getTitle(),
                "post_content" => $shortCode,
                "post_type" => "club_event",
                "post_status" => "publish",
                "guid" => $event->getUuid()
            ));
            $event->setPostId($postId);
            $event->save($id == null ? false : true);

            echo json_encode($event);
        }
        wp_die();
    }

    public function deletePlace() {
        if (array_key_exists("action", $_POST) && $_POST['action'] == 'club_calendar_delete_place') {
            $nonce = array_key_exists("nonce", $_POST) ? $_POST["nonce"] : null;
            $uuid = array_key_exists("uuid", $_POST) ? $_POST["uuid"] : null;
            if (wp_verify_nonce($nonce, "club-delete-place-" . $uuid)) {
                echo Calendar\Place::findById($uuid)->delete();
            }
        }
        wp_die();
    }

    public function deleteEvent() {
        if (array_key_exists("action", $_POST) && $_POST['action'] == 'club_calendar_delete_evnet') {
            $nonce = array_key_exists("nonce", $_POST) ? $_POST["nonce"] : null;
            $uuid = array_key_exists("uuid", $_POST) ? $_POST["uuid"] : null;
            if (wp_verify_nonce($nonce, "club-delete-event-" . $uuid)) {
                echo Calendar\Event::findById($uuid)->delete();
            }
        }
        wp_die();
    }

    public function linkFilter($link) {
        global $post;
        if ($post->post_type == "club_event") {
            return "";
        } else {
            return $link;
        }
    }

    // Add Shortcode
    public function eventShortCode($atts) {
        global $post;

        wp_register_style('bootstrap-admin', $this->_club->plugin_url . "/css/bootstrap-admin.css");
        wp_register_script("bootstrap-adminjs", $this->_club->plugin_url . "/js/bootstrap.min.js", array('jquery'));
        wp_enqueue_script('jquery');
        wp_enqueue_style('bootstrap-admin');
        wp_enqueue_script("bootstrap-adminjs");
        $this->loadGoogleMaps();

        // Attributes
        $atts = shortcode_atts(
                array(
            'event_id' => null,
            'type' => "event",
                ), $atts
        );
        $event_dir = $this->_club->public_folder . DIRECTORY_SEPARATOR . "event";
        switch ($atts["type"]) {
            case "event":
                if ($atts['event_id'] != NULL) {
                    $file = $event_dir . DIRECTORY_SEPARATOR . "event.php";
                    include $file;
                }
                break;
            case "overview":
                $file = $event_dir . DIRECTORY_SEPARATOR . "overview.php";
                include $file;
                break;
        }
    }

    private function loadGoogleMaps() {
        $maps_api = get_option('google_maps_api_key', '');
        if ($maps_api != '') {
            wp_enqueue_script(
                    'google-maps', '//maps.googleapis.com/maps/api/js?key=' . $maps_api . '&libraries=places', array(), '1.0', true
            );
            $this->maps_api_loaded = TRUE;
        }
    }

}
