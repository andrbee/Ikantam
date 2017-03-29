<?php
require "rb.php";
$config = require $_SERVER['DOCUMENT_ROOT'] . "/config.php";
R::setup('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'], $config['db_user'], $config['db_pass']);
session_start();?>