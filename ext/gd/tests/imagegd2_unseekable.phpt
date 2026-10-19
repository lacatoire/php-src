--TEST--
imagegd2() returns false when the compressed index cannot be written to an unseekable target
--EXTENSIONS--
gd
posix
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
if (!function_exists('proc_open')) die('skip proc_open is not available');
?>
--FILE--
<?php
$fifo = __DIR__ . '/imagegd2_unseekable.fifo';
posix_mkfifo($fifo, 0600);

$reader = proc_open(['cat', $fifo], [1 => ['file', '/dev/null', 'w']], $pipes);

$im = imagecreatetruecolor(200, 150);
imagefilledrectangle($im, 10, 10, 100, 100, 0xff0000);
var_dump(imagegd2($im, $fifo, 64, IMG_GD2_COMPRESSED));

proc_close($reader);
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/imagegd2_unseekable.fifo');
?>
--EXPECTF--
%A
bool(false)
