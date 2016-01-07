<?php

namespace Club\Admin;

use \Club\Admin\Module;

class Calendar extends Module {
    private $_club;

    public function init() {
        $this->_club->register_menu("Termine", "club_admin", "calendar.php");
    }

    public function setClub($club) {
        $this->_club = $club;
    }

}

