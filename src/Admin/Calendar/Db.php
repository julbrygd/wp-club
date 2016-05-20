<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Admin\Calendar;

/**
 * Description of Install
 *
 * @author stephan
 */
class Db {
    private static $EVENT_TABLE = "##WP_PREFIX##club_calendar_event";
    private static $PLACES_TABLE = "##WP_PREFIX##club_calendar_places";
    private static $SQL_PLACES = "CREATE TABLE `##PLACES_TABLE##` ( `placeid` VARCHAR(40) NOT NULL , `name` VARCHAR(255) NOT NULL , `lng` DOUBLE NOT NULL , `lat` DOUBLE NOT NULL , PRIMARY KEY (`placeid`)) ENGINE = InnoDB;";
    private static $SQL_EVENTS = "CREATE TABLE `##EVENT_TABLE##` ( `eventid` VARCHAR(40) NOT NULL , `title` VARCHAR(255) NOT NULL , `desc` TEXT NOT NULL , `from` DATETIME NOT NULL , `to` DATETIME NOT NULL , `placeid` VARCHAR(40) NOT NULL , PRIMARY KEY (`eventid`), INDEX `##WP_PREFIX##club_calendar_event_placeid` (`placeid`)) ENGINE = InnoDB;";
    private static $SQL_FK = "ALTER TABLE `##EVENT_TABLE##` ADD CONSTRAINT `club_calendar_event_place` FOREIGN KEY (`placeid`) REFERENCES `##PLACES_TABLE##`(`placeid`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
    private static $DB_VERSION = 3;
    private static $DB_UPDATE = array(
        2 => array(),
        3 => array()
    );
    
    public static function execute() {
        global $wpdb;
        self::$SQL_PLACES = str_replace("##PLACES_TABLE##", self::get_place_table(), self::$SQL_PLACES);
        self::$SQL_EVENTS = str_replace("##EVENT_TABLE##", self::get_event_table(), self::$SQL_EVENTS);
        self::$SQL_FK = str_replace("##EVENT_TABLE##", self::get_event_table(), self::$SQL_FK);
        self::$SQL_FK = str_replace("##PLACES_TABLE##", self::get_place_table(), self::$SQL_FK);
        /*
        var_dump(array(
            "SQL_PLACES" => self::$SQL_PLACES,
            "SQL_EVENTS" => self::$SQL_EVENTS,
            "SQL_FK" => self::$SQL_FK
        ));
        */ 
        $currentVersion = get_option("club_calendar_db_version", 0);
        if($currentVersion == 0){
            self::install();
        } else if ($currentVersion < self::$DB_VERSION) {
            self::update($currentVersion);
        }
    }
    
    protected static function install() {
        global $wpdb;
        $wpdb->query(self::$SQL_PLACES);
        $wpdb->query(self::$SQL_EVENTS);
        $wpdb->query(self::$SQL_FK);
        update_option("club_calendar_db_version", self::$DB_VERSION, FALSE);
    }

    protected static function update($currentVersion) {
        while ($currentVersion < self::$DB_VERSION){
            $currentVersion++;
            foreach (self::$DB_UPDATE[$currentVersion] as $sql) {
                global $wpdb;
                $sql = str_replace("##EVENT_TABLE##", self::get_event_table(), $sql);
                $sql = str_replace("##PLACES_TABLE##", self::get_event_table(), $sql);
                $wpdb->query($sql);
            }
        }
        update_option("club_calendar_db_version", self::$DB_VERSION, FALSE);
    }
    
    public static function get_event_table() {
        global $wpdb;
        return str_replace("##WP_PREFIX##", $wpdb->prefix, self::$EVENT_TABLE);
    }
    
    public static function get_place_table() {
        global $wpdb;
        return str_replace("##WP_PREFIX##", $wpdb->prefix, self::$PLACES_TABLE);
    }

}
