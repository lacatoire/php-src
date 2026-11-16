--TEST--
GH-2522: chown()/lchown() by user name must not spuriously fail on ZTS
--SKIPIF--
<?php
if (substr(PHP_OS, 0, 3) == 'WIN') die('skip no windows support');
if (!function_exists("posix_getpwuid")) die("skip no posix_getpwuid()");
?>
--FILE--
<?php
// php_get_uid_by_name() retried getpwnam_r() on EAGAIN instead of ERANGE
// (the actual "buffer too small" errno per POSIX), so a passwd entry
// exceeding the initial buffer size made chown()/lchown() by name fail
// with "Unable to find uid for <name>" even though the user exists.
$name = posix_getpwuid(posix_getuid())['name'];
$filename = __DIR__ . DIRECTORY_SEPARATOR . 'gh2522_chown_by_name.txt';

touch($filename);
var_dump(chown($filename, $name));
var_dump(lchown($filename, $name));

?>
--CLEAN--
<?php
unlink(__DIR__ . DIRECTORY_SEPARATOR . 'gh2522_chown_by_name.txt');
?>
--EXPECT--
bool(true)
bool(true)
