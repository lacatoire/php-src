--TEST--
sem_get(): max_acquire range, and sem_remove() on a set without permission bits
--EXTENSIONS--
sysvsem
posix
--SKIPIF--
<?php
if (posix_geteuid() === 0) die('skip a privileged user passes the permission checks');
?>
--FILE--
<?php
$key = ftok(__FILE__, 'm');

foreach ([0, -1, 2**31] as $n) {
    try {
        var_dump(sem_get($key, $n));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}

// Above the kernel limit: no unusable object
var_dump(sem_get($key, 2**31 - 1));

// A set without permission bits can still be removed by its creator
$p = @sem_get($key, 1, 0);
var_dump($p === false || sem_remove($p));
?>
--EXPECTF--
ValueError: sem_get(): Argument #2 ($max_acquire) must be between 1 and 2147483647
ValueError: sem_get(): Argument #2 ($max_acquire) must be between 1 and 2147483647
ValueError: sem_get(): Argument #2 ($max_acquire) must be between 1 and 2147483647

Warning: sem_get(): Failed setting max_acquire to 2147483647 for key 0x%x: Numerical result out of range in %s on line %d
bool(false)
bool(true)
