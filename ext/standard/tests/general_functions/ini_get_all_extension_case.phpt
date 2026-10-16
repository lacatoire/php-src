--TEST--
ini_get_all(): the extension name is matched case-insensitively
--FILE--
<?php
var_dump(ini_get_all('Standard', false) === ini_get_all('standard', false));
var_dump(ini_get_all('STANDARD', false) === ini_get_all('standard', false));
var_dump(ini_get_all('Nope', false));
?>
--EXPECTF--
bool(true)
bool(true)

Warning: ini_get_all(): Extension "Nope" cannot be found in %s on line %d
bool(false)
