--TEST--
file://localhost/ URLs are resolved by the filesystem functions as by is_dir()
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') {
    die('skip file://localhost paths are not handled the same way on Windows');
}
?>
--FILE--
<?php

$base = __DIR__ . '/file_localhost_url';
mkdir($base);
$url = 'file://localhost' . $base;

var_dump(mkdir("$url/dir"));
var_dump(is_dir("$base/dir"));
var_dump(touch("$url/file"));
var_dump(file_exists("$base/file"));
var_dump(rename("$url/file", "$url/renamed"));
var_dump(file_exists("$base/renamed"));
var_dump(unlink("$url/renamed"));
var_dump(file_exists("$base/renamed"));
var_dump(rmdir("$url/dir"));
var_dump(is_dir("$base/dir"));

?>
--CLEAN--
<?php
$base = __DIR__ . '/file_localhost_url';
@rmdir("$base/dir");
@unlink("$base/file");
@unlink("$base/renamed");
@rmdir($base);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(false)
bool(true)
bool(false)
