<?php

namespace Club\Admin;

abstract class Module{
    public abstract function setClub($club);
    public abstract function init();
}