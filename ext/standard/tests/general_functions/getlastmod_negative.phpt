--TEST--
getlastmod(): the modification time of a script older than 1970
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip negative mtime not supported on Windows');
$f = __DIR__ . '/getlastmod_negative_probe.php';
file_put_contents($f, '<?php');
$ok = @touch($f, -31536000) && filemtime($f) === -31536000;
@unlink($f);
if (!$ok) die('skip the file system does not store a negative mtime');
?>
--FILE--
<?php
$f = __DIR__ . '/getlastmod_negative_probe.php';
file_put_contents($f, '<?php var_dump(getlastmod(), filemtime(__FILE__));');
touch($f, -31536000);
echo shell_exec(escapeshellarg(PHP_BINARY) . ' -n ' . escapeshellarg($f));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/getlastmod_negative_probe.php');
?>
--EXPECT--
int(-31536000)
int(-31536000)
