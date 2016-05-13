<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Admin\Calendar;

use Ramsey\Uuid\Uuid;

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

    public function __construct($uuid = null) {
        if ($uuid == NULL) {
            $this->uuid = Uuid::uuid4()->toString();
        } else {
            $this->uuid = $uuid;
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

    public function fromPost($post) {
        $this->lng = $post["lng"];
        $this->lat = $post["lat"];
        $this->name = $post["place"];
    }

}
