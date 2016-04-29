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
    }

    public function public_init() {
        $this->urlHandler();
    }

}
