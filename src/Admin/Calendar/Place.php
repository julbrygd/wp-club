<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Admin\Calendar;

use Ramsey\Uuid\Uuid;
use \Club\Admin\Calendar\Db;

/**
 * Description of Place
 *
 * @author stephan
 */
class Place implements \JsonSerializable {

    /**
     *
     * @var String
     */
    private $uuid;

    /**
     * 
     * @var String
     */
    private $name;

    /**
     *
     * @var Double
     */
    private $lng;

    /**
     *
     * @var Double
     */
    private $lat;

    /**
     *
     * @var Boolean
     */
    private $new;

    public function __construct($uuid = null) {
        if ($uuid == NULL) {
            $this->uuid = Uuid::uuid4()->toString();
            $this->new = true;
        } else {
            $this->uuid = $uuid;
            $this->new = false;
        }
    }

    public function jsonSerialize() {
        return array(
            "uuid" => $this->uuid,
            "name" => $this->name,
            "lng" => $this->lng,
            "lat" => $this->lat
        );
    }

    public function getUuid() {
        return $this->uuid;
    }

    public function getName() {
        return $this->name;
    }

    public function getLng() {
        return $this->lng;
    }

    public function getLat() {
        return $this->lat;
    }

    public function setUuid($uuid) {
        $this->uuid = $uuid;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setLng($lng) {
        $this->lng = $lng;
        return $this;
    }

    public function setLat($lat) {
        $this->lat = $lat;
        return $this;
    }
    
    private static function fromStdClass($obj){
        $ret = new self($obj->placeid);
        $ret->setName($obj->name);
        $ret->setLat($obj->lat);
        $ret->setLng($obj->lng);
        return $ret;
    }

    public function fromPost($post) {
        $this->lng = $post["lng"];
        $this->lat = $post["lat"];
        $this->name = $post["place"];
    }
    
    public function delete() {
        global $wpdb;
        return 1 == $wpdb->delete(Db::get_place_table(), array("placeid" => $this->uuid));
    }

    public function save($update = true) {
        global $wpdb;
        $tablename = Db::get_place_table();

        if ($this->new) {
            $wpdb->insert(
                    $tablename, array(
                "placeid" => $this->uuid,
                "name" => $this->name,
                "lng" => $this->lng,
                "lat" => $this->lat,
                    )
            );
            $this->new = false;
        } else if ($update) {
            $wpdb->update(
                    $tablename, array(
                "name" => $this->name,
                "lng" => $this->lng,
                "lat" => $this->lat,
                    ), array(
                "placeid" => $this->uuid
            ));
        }
    }
    
    public static function findById($id){
        global $wpdb;
        return self::fromStdClass($wpdb->get_row( 
                "SELECT * FROM " . Db::get_place_table(). " WHERE `" . Db::get_place_table() ."`.`placeid` = '".$id."'" 
                ));
    }
    
    public static function getAll(){
        global $wpdb;
        $ret = array();
        foreach ($wpdb->get_results( "SELECT * FROM `" . Db::get_place_table() ."`" ) as $obj){
            $ret[] = self::fromStdClass($obj);
        }
        return $ret;
    }

}
