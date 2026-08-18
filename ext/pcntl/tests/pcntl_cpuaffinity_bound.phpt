--TEST--
pcntl_setcpuaffinity(): the upper bound the error advertises is itself a valid cpu id
--EXTENSIONS--
pcntl posix
--SKIPIF--
<?php
if (!function_exists('pcntl_setcpuaffinity')) die('skip pcntl_setcpuaffinity() is not available');
?>
--FILE--
<?php
$pid = posix_getpid();
$affinity = pcntl_getcpuaffinity($pid);

/* read the bound out of the message itself */
try {
    pcntl_setcpuaffinity($pid, [PHP_INT_MAX]);
} catch (ValueError $e) {
    preg_match('/must be between 0 and (\d+)/', $e->getMessage(), $matches);
}
$bound = (int) $matches[1];

/* every cpu the process may run on has to be inside the advertised range */
var_dump(max($affinity) <= $bound);

/* the bound is inclusive, so it must not be refused; the call itself may still
 * fail on a restricted cpu set, which is not what is under test here */
try {
    @pcntl_setcpuaffinity($pid, [$bound]);
    echo "bound accepted\n";
} catch (ValueError $e) {
    echo "bound refused: ", $e->getMessage(), "\n";
}

/* and the first id past it must be refused */
try {
    pcntl_setcpuaffinity($pid, [$bound + 1]);
} catch (ValueError $e) {
    var_dump(str_contains($e->getMessage(), "cpu id must be between 0 and {$bound} (" . ($bound + 1) . ")"));
}

pcntl_setcpuaffinity($pid, $affinity);
?>
--EXPECT--
bool(true)
bound accepted
bool(true)
