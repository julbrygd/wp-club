<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule\Types;

/**
 * Description of String
 *
 * @author stephan
 */
class String extends AbstractType {

    public function __construct($name, $displayName, $list) {
        parent::__construct($name, $displayName, $list);
        $this->options = array(
            "length" => 20,
            "null" => false
        );
    }

    public function getAddStatement() {
        $statements = array(
            sprintf("alter table %s ADD %s varchar(%d);", $this->list, $this->name, $this->options["length"])
        );
        if($this->options["null"]) {
            $statements[] = sprintf("ALTER TABLE %s MODIFY COLUMN %s NOT NULL;", $this->list, $this->name);
        }
        return implode("\n", $statements);
    }

    public function getCreateStatement() {
        $null="";
        if($this->options["null"]){
            $null = " NOT NULL";
        }
        return sprintf("%s varchar(%d)%s", $this->name, $this->options["length"], $null);
    }

    public function getDeleteStatement() {
        return "";
    }

    public function getEditStatement() {
        return "";
    }

}
