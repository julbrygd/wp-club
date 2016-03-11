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
        global $wpdb;

        $table_name = $wpdb->prefix . "club_calendar_categories";
        $table_name_categories = $table_name;
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name varchar(255) NOT NULL
	) $charset_collate;";

            //reference to upgrade.php file
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
        }
        
        $table_name = $wpdb->prefix . "club_calendar_dates";
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		start datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                end datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		title varchar(255) NOT NULL,
		description text NOT NULL,
		url varchar(255) DEFAULT '' NOT NULL,
                lat double DEFAULT 0.0 NOT NULL,
                lng double DEFAULT 0.0 NOT NULL,
                category int
	) $charset_collate;";

            //reference to upgrade.php file
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
        }
    }

}
