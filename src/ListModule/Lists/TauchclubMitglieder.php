<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule\Lists;

/**
 * Description of TauchclubMitglieder
 *
 * @author stephan
 */
class TauchclubMitglieder extends ListDescriptor {
    
    public static $name = "Tauchclub Mitglieder List";

    public static function getClass() {
        return '\Club\Models\ListModule\Lists\MitgliederTaucher';
    }

    public function getEditForm() {
        return "";
    }

    public function getFields() {
        return "";
    }

    public function getListName() {
        return "";
    }

    public function getListTemplate() {
        return "";
    }
    
    public static function getDescriptor($class) {
        return array(
            "name" => self::$name,
            "description" => "Miglieder Liste, welche für Tauchclubs Optimiert ist",
            "class" => $class
        );
    }

    public function getMetaData() {
        return array(
            $this->em->getClassMetadata('Club\Models\ListModule\Lists\MitgliederTaucher'),
            $this->em->getClassMetadata('Club\Models\ListModule\Lists\Plz'),
            $this->em->getClassMetadata('Club\Models\ListModule\Lists\TaucherBrevets'),
            $this->em->getClassMetadata('Club\Models\ListModule\Lists\PhoneNumber')
        );
    }

    public static function getName() {
        return self::$name;
    }

}
