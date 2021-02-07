<?php
namespace public_src;

use Exception;
use ParseError;

define("ROOT", dirname(__DIR__) . DIRECTORY_SEPARATOR); 
if (! file_exists(ROOT . "hub/config.php") || ! file_exists(ROOT . "hub/Core.php"))
    die();

require ROOT . "hub/config.php";
require ROOT . "hub/Core.php";

// Startuj Aplikaciju. Sretno.
$core = new \hub\Core();

try {
    $core->route();
} catch (ParseError $parseErr) {
    $core->error_handler($parseErr->getCode(), $parseErr->getMessage(), $parseErr->getFile(), $parseErr->getLine());
    die();
} catch (Exception $e) {
    $core->error_handler($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
    die();
}
