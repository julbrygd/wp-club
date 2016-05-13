<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Admin\Calendar;

use Ramsey\Uuid\Uuid;

/**
 * Description of Event
 *
 * @author stephan
 */
class Event implements \JsonSerializable {

    /**
     *
     * @var String
     */
    private $uuid;

    /**
     *
     * @var String
     */
    private $title;

    /**
     *
     * @var String
     */
    private $descripion;

    /**
     *
     * @var \DateTime
     */
    private $from;

    /**
     *
     * @var \DateTime
     */
    private $to;

    /**
     *
     * @var \Club\Admin\Calendar\Place
     */
    public $place;

    public function __construct($uuid = null) {
        if ($uuid == NULL) {
            $this->uuid = Uuid::uuid4()->toString();
        } else {
            $this->uuid = $uuid;
        }
    }

    public function getUuid() {
        return $this->uuid;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescripion() {
        return $this->descripion;
    }

    public function getFrom() {
        return $this->from;
    }

    public function getTo() {
        return $this->to;
    }

    public function getPlace() {
        return $this->place;
    }

    public function setUuid($uuid) {
        $this->uuid = $uuid;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setDescripion($descripion) {
        $this->descripion = $descripion;
        return $this;
    }

    public function setFrom($from) {
        $this->from = $from;
        return $this;
    }

    public function setTo($to) {
        $this->to = $to;
        return $this;
    }

    public function setPlace(Place $place) {
        $this->place = $place;
        return $this;
    }

    public function jsonSerialize() {
        return array(
            "uuid" => $this->uuid,
            "title" => $this->title,
            "description" => $this->descripion,
            "from" => $this->from,
            "to" => $this->to,
            "place" => $this->place
        );
    }

    public function fromPost($post, $dateFormat) {
        $this->title = $post["title"];
        $this->descripion = $post["desc"];
        $this->from = \DateTime::createFromFormat($dateFormat, $post["from"]);
        $this->to = \DateTime::createFromFormat($dateFormat, $post["to"]);
    }

}
