--TEST--
shmop_open() removes the segment it created when it fails afterwards
--EXTENSIONS--
shmop
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

$key = ftok(__FILE__, 't');

// Mode 0 denies even the owner, so shmctl(IPC_STAT) fails once the segment exists.
foreach (['n', 'c'] as $flags) {
    var_dump(shmop_open($key, $flags, 0, 100));
    // The failed call must not leave the key behind.
    $shm = shmop_open($key, 'n', 0600, 100);
    var_dump($shm instanceof Shmop);
    shmop_delete($shm);
}

// A segment that already exists must survive a failing "c" open.
$existing = shmop_open($key, 'n', 0600, 100);
var_dump(shmop_open($key, 'c', 0600, 200));
var_dump(shmop_open($key, 'w', 0, 0) instanceof Shmop);
shmop_delete($existing);

?>
--EXPECTF--
Warning: shmop_open(): Unable to get shared memory segment information "%s" in %s on line %d
bool(false)
bool(true)

Warning: shmop_open(): Unable to get shared memory segment information "%s" in %s on line %d
bool(false)
bool(true)

Warning: shmop_open(): Unable to attach or create shared memory segment "%s" in %s on line %d
bool(false)
bool(true)
