<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once("includes/config.php");
require_once("includes/controller.php");

$controller = new Controller;

$controller->handleAction();
