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
        if (is_admin()) return;
        $cal = \Club\Calendar\CalendarServer::getInstance();
        $uri = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        if($uri == $cal->base_uri) {
            $cal->start();
            wp_die();
        }
    }

    public function public_init() {
        global $wp_rewrite;
        add_rewrite_rule('/club/caldav*', 'index.php');
        $wp_rewrite->flush_rules(true); 
        $this->urlHandler();
    }

}
