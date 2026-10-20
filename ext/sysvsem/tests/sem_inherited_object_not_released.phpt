--TEST--
A SysvSemaphore inherited by a forked child does not release the semaphore of the parent
--EXTENSIONS--
sysvsem
pcntl
--SKIPIF--
<?php
if (!function_exists('ftok')) {
    die('skip ftok() is not available');
}
?>
--FILE--
<?php

$key = ftok(__FILE__, 'i');
$semaphore = sem_get($key, 1, 0600);
$other = sem_get($key, 1, 0600);
var_dump(sem_acquire($semaphore));

// The child destroys its copy of $semaphore, then stays alive: the kernel only undoes
// the adjustments of a process when it exits.
$pid = pcntl_fork();
if ($pid === 0) {
    unset($semaphore);
    sleep(2);
    exit(0);
}
usleep(500000);

// The parent still holds the semaphore, so it cannot be acquired through another handle.
var_dump(sem_acquire($other, true));
pcntl_waitpid($pid, $status);

var_dump(sem_release($semaphore));
var_dump(sem_acquire($other, true));
sem_release($other);
sem_remove($other);

?>
--EXPECT--
bool(true)
bool(false)
bool(true)
bool(true)
