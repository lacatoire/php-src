--TEST--
sem_get() returns false instead of blocking when the usage counter is exhausted
--EXTENSIONS--
sysvsem
--SKIPIF--
<?php
if (!function_exists('ftok')) {
    die('skip ftok() is not available');
}
?>
--FILE--
<?php

$key = ftok(__FILE__, 'u');
$keep = sem_get($key, 1, 0600, false);
$failures = 0;

// With auto_release disabled, every discarded object leaves the usage counter one higher.
for ($i = 0; $i < 33000; $i++) {
    if (@sem_get($key, 1, 0600, false) === false) {
        $failures++;
    }
}
var_dump($failures > 0);
var_dump(sem_get($key, 1, 0600, false));
var_dump(sem_remove($keep));

?>
--EXPECTF--
bool(true)

Warning: sem_get(): Failed acquiring SYSVSEM_SETVAL for key 0x%s: Numerical result out of range in %s on line %d
bool(false)
bool(true)
