--TEST--
sem_remove() removes a set that was created without permission bits
--EXTENSIONS--
sysvsem
--SKIPIF--
<?php
if (function_exists('posix_geteuid') && posix_geteuid() === 0) {
    die('skip root bypasses the permission checks this test relies on');
}
if (!function_exists('ftok')) {
    die('skip ftok() is not available');
}
?>
--FILE--
<?php

$key = ftok(__FILE__, 's');

// Without permission bits, even the owner cannot stat the set, but can remove it.
$semaphore = @sem_get($key, 1, 0);
var_dump(sem_remove($semaphore));
$semaphore = sem_get($key, 1, 0600);
var_dump($semaphore instanceof SysvSemaphore);
var_dump(sem_remove($semaphore));

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
