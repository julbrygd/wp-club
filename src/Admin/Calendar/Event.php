<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Admin\Calendar;

use Ramsey\Uuid\Uuid;
use Club\Admin\Calendar\Place;

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
    private $place;

    /**
     *
     * @var bool
     */
    private $new;

    /**
     * 
     * var integer
     */
    private $postId;

    public function __construct($uuid = null) {
        if ($uuid == NULL) {
            $this->uuid = Uuid::uuid4()->toString();
            $this->new = TRUE;
        } else {
            $this->uuid = $uuid;
            $this->new = FALSE;
        }
        $this->postId = 0;
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

    public function getFromFormated($time = TRUE) {
        $fmt = get_option('date_format');
        if($time){
            $fmt .= " " . get_option("time_format");
        }
        return $this->from->format($fmt);
    }

    public function getToFormated() {
        $fmt = get_option('date_format') . " " . get_option("time_format");
        return $this->to->format($fmt);
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

    /**
     * 
     * @return integer
     */
    public function getPostId() {
        return $this->postId;
    }

    /**
     * 
     * @param integer $postId
     * @return \Club\Admin\Calendar\Event
     */
    public function setPostId($postId) {
        $this->postId = $postId;
        return $this;
    }

    public function jsonSerialize() {
        return array(
            "uuid" => $this->uuid,
            "title" => $this->title,
            "description" => $this->descripion,
            "from" => $this->from->getTimestamp(),
            "from_offset" => $this->from->getOffset(),
            "to" => $this->to->getTimestamp(),
            "to_offset" => $this->to->getOffset(),
            "place" => $this->place,
            "postId" => $this->postId
        );
    }

    public function fromPost($post, $dateFormat) {
        $this->title = $post["title"];
        $this->descripion = $post["desc"];
        if ($post["from"] != null || $post["from"] != "") {
            $this->from = \DateTime::createFromFormat($dateFormat, $post["from"], new \DateTimeZone("Etc/UTC"));
        } else {
            $this->from = null;
        }
        if ($post["to"] != null || $post["to"] != "") {
            $this->to = \DateTime::createFromFormat($dateFormat, $post["to"], new \DateTimeZone("Etc/UTC"));
        } else {
            $this->to = null;
        }
    }

    /**
     * 
     * @return \stdClass Result of the check
     */
    public function check() {
        $ret = new \stdClass();
        $ret->error = false;
        $ret->messages = array();
        if ($this->title == null || $this->title == "") {
            $ret->error = true;
            $ret->message["title"] = "Der Titel darf nicht leer seint";
        }
        if ($this->descripion == null || $this->descripion == "") {
            $ret->error = true;
            $ret->message["descripion"] = "Die Beschreibung darf nicht leer seint";
        }
        if ($this->from == null) {
            $ret->error = true;
            $ret->message["from"] = "Die \"Von\" Zeit darf nicht leer seint";
        }
        if ($this->to == null) {
            $ret->error = true;
            $ret->message["to"] = "Die \"Bis\" Zeit darf nicht leer seint";
        }
        if ($this->from == false) {
            $ret->error = true;
            $ret->message["from"] = "Die \"Von\" Zeit konnte nicht in ein Datum umgewandelt werden";
        }
        if ($this->to == false) {
            $ret->error = true;
            $ret->message["to"] = "Die \"Bis\" Zeit konnte nicht in ein Datum umgewandelt werden";
        }
        return $ret;
    }

    public function save($update) {
        global $wpdb;
        $tablename = Db::get_event_table();

        if ($this->new) {
            $this->from->setTimezone(new \DateTimeZone("UTC"));
            $this->to->setTimezone(new \DateTimeZone("UTC"));
            $wpdb->insert(
                    $tablename, array(
                "eventid" => $this->uuid,
                "title" => $this->title,
                "desc" => $this->descripion,
                "from" => $this->from->format('Y-m-d H:i:s'),
                "to" => $this->to->format('Y-m-d H:i:s'),
                "placeid" => $this->place->getUuid(),
                "postid" => $this->postId
                    )
            );
            $this->new = false;
        } else if ($update) {
            $wpdb->update(
                    $tablename, array(
                "title" => $this->title,
                "desc" => $this->descripion,
                "from" => $this->from->format('Y-m-d H:i:s'),
                "to" => $this->to->format('Y-m-d H:i:s'),
                "placeid" => $this->place->getUuid(),
                "postid" => $this->postId
                    ), array(
                "eventid" => $this->uuid
            ));
        }
    }

    protected static function fromStdClass($obj) {
        $ret = new self($obj->eventid);
        $ret->setTitle($obj->title);
        $ret->setDescripion($obj->desc);
        $ret->setFrom(\DateTime::createFromFormat('Y-m-d H:i:s', $obj->from, new \DateTimeZone("UTC")));
        $ret->setTo(\DateTime::createFromFormat('Y-m-d H:i:s', $obj->to, new \DateTimeZone("UTC")));
        $ret->getFrom()->setTimezone(new \DateTimeZone(get_option('timezone_string')));
        $ret->getTo()->setTimezone(new \DateTimeZone(get_option('timezone_string')));
        $ret->setPlace(Place::findById($obj->placeid));
        $ret->setPostId($obj->postid);
        return $ret;
    }

    /**
     * 
     * @global \Club\Admin\Calendar\type $wpdb
     * @param \DateTime $after
     * @param integer $limit
     * @return @return \Club\Admin\Calendar\Event
     */
    public static function getAll($after = null, $limit = null) {
        global $wpdb;
        $ret = array();
        $where="";
        if($after != null){
            $where = " WHERE `from` >= '" . $after->format('Y-m-d H:i:s') ."' ";
        }
        $limitSQL = "";
        if($limit != null){
            $limitSQL = " LIMIT ".$limit;
        }
        foreach ($wpdb->get_results("SELECT * FROM `" . Db::get_event_table() . "`".$where." ORDER BY `from`".$limitSQL) as $obj) {
            $ret[] = self::fromStdClass($obj);
        }
        return $ret;
    }

    /**
     * 
     * @global type $wpdb
     * @param interger $id
     * @return \Club\Admin\Calendar\Event
     */
    public static function findById($id) {
        global $wpdb;
        return self::fromStdClass($wpdb->get_row(
                                "SELECT * FROM " . Db::get_event_table() . " WHERE `" . Db::get_event_table() . "`.`eventid` = '" . $id . "'"
        ));
    }

    public function delete() {
        global $wpdb;
        return 1 == $wpdb->delete(Db::get_event_table(), array("eventid" => $this->uuid));
    }

}
