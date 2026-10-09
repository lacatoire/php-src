--TEST--
shm_get_var() survives an autoloader that detaches the segment
--EXTENSIONS--
sysvshm
shmop
--FILE--
<?php

class Zzz
{
    public $p = "padding-padding-padding-padding";
    public $q = [1, 2, 3];
}

$key = ftok(__FILE__, 'g');
$shm = shm_attach($key, 100000, 0600);
shm_put_var($shm, 1, new Zzz);

// Rewrite the class name to one that is not loaded, to trigger the autoloader.
$raw = shmop_open($key, "w", 0, 0);
$pos = strpos(shmop_read($raw, 0, shmop_size($raw)), 'O:3:"Zzz"');
shmop_write($raw, 'O:3:"Yyy"', $pos);

spl_autoload_register(function ($class) use ($shm) {
    echo "autoload($class): detaching\n";
    shm_detach($shm);
});

$value = shm_get_var($shm, 1);
var_dump($value::class);

try {
    shm_get_var($shm, 1);
} catch (Error $e) {
    echo $e->getMessage(), "\n";
}

shmop_delete($raw);

?>
--EXPECT--
autoload(Yyy): detaching
string(22) "__PHP_Incomplete_Class"
Shared memory block has already been destroyed
