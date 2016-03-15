<?php

namespace Club\Admin;

use \Club\Admin\Module;

class Calendar extends Module {

    private $_club;

    public function init() {
        $this->install();
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
<<<<<<< Updated upstream
        if (is_admin()) return;
        $cal = \Club\Calendar\CalendarServer::getInstance();
        $uri = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        if($uri == $cal->base_uri) {
=======
        if (is_admin())
            return;
        $cal = \Club\Calendar\CalendarServer::getInstance();
        $uri = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'];
        if ($uri == $cal->base_uri) {
>>>>>>> Stashed changes
            $cal->start();
            wp_die();
        }
    }

    public function public_init() {
        $router = new \PluginEndpoints\Router();

// register an endpoint
        //$router->register_endpoint('/club/caldav', array('club/club.php'));
        $this->urlHandler();
    }

}
