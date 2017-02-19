<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
set_include_path(dirname(__FILE__));

include_once 'config.php';


function __autoload($class_name) {
	require_once 'model/'.$class_name.'.class.php';
}

//default session is set to guest. This is to identify unregistered users.
session_start();
if(!isset($_SESSION['user'])){
    $_SESSION['user'] = "guest";
}