<?php 

define("ROOT", dirname(__DIR__) . DIRECTORY_SEPARATOR); // Definisi root fajl aplikacije.

require ROOT . "core/config.php"; // Fajl sa izmjenjivim podacima.
require ROOT . "core/core.php"; // Fajl sa funkcijama koje su neophodne za koristenje MVC aplikacije.

// Startuj Aplikaciju. Sretno.
$core = new Core();
$core->route();
