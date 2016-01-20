<?php

namespace Club\Admin;

use \Club\Admin\Module;

class Calendar extends Module {
    private $_club;

    public function init() {
        
    }

    public function setClub($club) {
        $this->_club = $club;
    }

    public function addMenu() {
        $this->_club->add_menu("Termine", "club_admin", "club/admin/calendar.php");
    }

}

