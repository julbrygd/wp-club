<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo Club\Calendar\CalendarServer::getInstance()->base_uri . '<br>';
echo var_dump($_SERVER);
$uri = $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI'];
echo $uri;
//echo var_dump(Club\Club::getInstance()->getModules());