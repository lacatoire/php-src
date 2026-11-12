--TEST--
mkdir() and link() clear the stat cache
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') {
    die('skip not for Windows');
}
?>
--FILE--
<?php
$dir = __DIR__ . '/stat_cache_mkdir_link_dir';
mkdir($dir);
var_dump(stat($dir)['nlink']);
mkdir($dir . '/a');
mkdir($dir . '/b/c', 0777, true);
var_dump(stat($dir)['nlink']);

$file = $dir . '/file.txt';
file_put_contents($file, 'x');
var_dump(stat($file)['nlink']);
link($file, $dir . '/hard.txt');
var_dump(stat($file)['nlink']);

?>
--CLEAN--
<?php
$dir = __DIR__ . '/stat_cache_mkdir_link_dir';
@unlink($dir . '/hard.txt');
@unlink($dir . '/file.txt');
@rmdir($dir . '/b/c');
@rmdir($dir . '/b');
@rmdir($dir . '/a');
@rmdir($dir);
?>
--EXPECT--
int(2)
int(4)
int(1)
int(2)
