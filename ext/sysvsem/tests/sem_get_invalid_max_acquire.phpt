--TEST--
sem_get() does not return an unusable semaphore, sem_remove() removes a set without permission bits
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

// Above SEMVMX, so that semctl(SETVAL) refuses it; the failed call must not leave the set behind.
var_dump(sem_get($key, PHP_INT_MAX >> 40));
$semaphore = sem_get($key, 1, 0600);
var_dump($semaphore instanceof SysvSemaphore);
var_dump(sem_acquire($semaphore, true));
var_dump(sem_remove($semaphore));

// Without permission bits, even the owner cannot stat the set, but can remove it.
$semaphore = @sem_get($key, 1, 0);
var_dump(sem_remove($semaphore));
$semaphore = sem_get($key, 1, 0600);
var_dump($semaphore instanceof SysvSemaphore);
sem_remove($semaphore);

?>
--EXPECTF--
Warning: sem_get(): Failed for key 0x%s: Numerical result out of range in %s on line %d
bool(false)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
