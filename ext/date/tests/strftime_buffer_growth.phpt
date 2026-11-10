--TEST--
strftime(), gmstrftime(): the buffer grows up to 8192 bytes
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip locale dependent');
?>
--FILE--
<?php
error_reporting(E_ALL & ~E_DEPRECATED);
foreach ([255, 256, 4095, 4096, 8191, 8192, 20000] as $n) {
    $r = gmstrftime(str_repeat('x', $n), 0);
    echo $n, ' -> ', $r === false ? 'false' : strlen($r), "\n";
}
?>
--EXPECT--
255 -> 255
256 -> 256
4095 -> 4095
4096 -> 4096
8191 -> 8191
8192 -> false
20000 -> false
