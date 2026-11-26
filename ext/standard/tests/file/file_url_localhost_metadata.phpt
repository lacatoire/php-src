--TEST--
file://localhost/ URLs resolve to the absolute path in touch(), chmod(), rename(), unlink(), mkdir() and rmdir()
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
?>
--FILE--
<?php
$dir = __DIR__ . '/file_url_localhost_metadata';
mkdir($dir);
chdir($dir);
$url = 'file://localhost' . $dir;

var_dump(touch("$url/a", 1000));
var_dump(filemtime("$dir/a"));
var_dump(chmod("$url/a", 0600));
var_dump(rename("$url/a", "$url/b"));
var_dump(file_exists("$dir/b"));
var_dump(unlink("$url/b"));
var_dump(file_exists("$dir/b"));
var_dump(mkdir("$url/sub"));
var_dump(is_dir("$dir/sub"));
var_dump(rmdir("$url/sub"));
var_dump(is_dir("$dir/sub"));
var_dump(file_exists("$dir/localhost"));
?>
--CLEAN--
<?php
$dir = __DIR__ . '/file_url_localhost_metadata';
@unlink("$dir/a");
@unlink("$dir/b");
@rmdir("$dir/sub");
@rmdir($dir);
?>
--EXPECT--
bool(true)
int(1000)
bool(true)
bool(true)
bool(true)
bool(true)
bool(false)
bool(true)
bool(true)
bool(true)
bool(false)
bool(false)
