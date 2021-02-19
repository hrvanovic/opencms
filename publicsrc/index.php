<?php

namespace public_src;

use Exception;
use ParseError;
use hub\Core;

define("ROOT", dirname(__DIR__) . DIRECTORY_SEPARATOR); // Definisi root fajl aplikacije.
if (!file_exists(ROOT . "hub/config.php") || !file_exists(ROOT . "hub/Core.php"))
    die();

require ROOT . "hub/config.php"; // Fajl sa izmjenjivim podacima.
require ROOT . "hub/Core.php";

// Startuj Aplikaciju. Sretno.

try {
    $core = new Core;
    $core->route();

} catch (ParseError $parseErr) {
    echo "Dogodila se greska!";
    die();
}
