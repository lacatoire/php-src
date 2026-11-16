--TEST--
shm_put_var() keeps the previous value when an update does not fit
--EXTENSIONS--
sysvshm
--FILE--
<?php

$key = ftok(__FILE__, 'p');
$shm = shm_attach($key, 1000, 0600);

var_dump(shm_put_var($shm, 1, "original"));
var_dump(shm_put_var($shm, 2, "other"));
var_dump(shm_put_var($shm, 1, str_repeat("x", 5000)));
var_dump(shm_has_var($shm, 1), shm_get_var($shm, 1), shm_get_var($shm, 2));

// An update that fits once the old value is reclaimed still works.
var_dump(shm_put_var($shm, 1, "updated"));
var_dump(shm_get_var($shm, 1), shm_get_var($shm, 2));

shm_remove($shm);

?>
--EXPECTF--
bool(true)
bool(true)

Warning: shm_put_var(): Not enough shared memory left in %s on line %d
bool(false)
bool(true)
string(8) "original"
string(5) "other"
bool(true)
string(7) "updated"
string(5) "other"
