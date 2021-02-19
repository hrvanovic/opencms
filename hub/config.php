<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace hub;

define("ROOT_APP", "/opencms" . DIRECTORY_SEPARATOR);

define("DB_TYPE", "PDO"); // MySQL || PDO
define("DB_PREFIX", "");
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "opencms");

if(isset($_SERVER["SERVER_NAME"]))
define("URL", $_SERVER["SERVER_NAME"]);

define("ROOT_ASSETS", ROOT . "publicsrc/assets/");
define("ROOT_JS", ROOT_APP . "assets/js/");
define("ROOT_CSS", ROOT_APP . "assets/css/");

define("ROOT_VIEW", ROOT . "view" . DIRECTORY_SEPARATOR);
define("ROOT_MODEL", ROOT . "model" . DIRECTORY_SEPARATOR);

define("ROOT_CONTROLLER", ROOT . "controller" . DIRECTORY_SEPARATOR);
define("ROOT_CONTROLLER_ERROR", ROOT . "/controller" . DIRECTORY_SEPARATOR . "ErrorController.php");
define("ROOT_CONTROLLER_INDEX", ROOT . "/controller" . DIRECTORY_SEPARATOR . "IndexController.php");

define("ROOT_LOGS", ROOT . "logs/");
