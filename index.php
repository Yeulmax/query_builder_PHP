<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'autoloader.php';

use App\Core\ConstantLoader;
use App\Router;

new ConstantLoader();
new Router();
