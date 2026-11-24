--TEST--
stat() functions check open_basedir for a path served from the stat cache
--FILE--
<?php
$dir = __DIR__ . '/stat_cache_open_basedir_dir';
mkdir($dir . '/sub', 0777, true);
file_put_contents($dir . '/f.txt', 'hello');

ini_set('open_basedir', $dir);
var_dump(stat($dir . '/f.txt')['size']);
var_dump(filesize($dir . '/f.txt'));

ini_set('open_basedir', $dir . '/sub');
var_dump(stat($dir . '/f.txt'));
var_dump(filesize($dir . '/f.txt'));
var_dump(is_file($dir . '/f.txt'));
var_dump(file_exists($dir . '/f.txt'));
?>
--CLEAN--
<?php
$dir = __DIR__ . '/stat_cache_open_basedir_dir';
@unlink($dir . '/f.txt');
@rmdir($dir . '/sub');
@rmdir($dir);
?>
--EXPECTF--
int(5)
int(5)

Warning: stat(): open_basedir restriction in effect. File(%s/f.txt) is not within the allowed path(s): (%s/sub) in %s on line %d
bool(false)

Warning: filesize(): open_basedir restriction in effect. File(%s/f.txt) is not within the allowed path(s): (%s/sub) in %s on line %d
bool(false)

Warning: is_file(): open_basedir restriction in effect. File(%s/f.txt) is not within the allowed path(s): (%s/sub) in %s on line %d
bool(false)

Warning: file_exists(): open_basedir restriction in effect. File(%s/f.txt) is not within the allowed path(s): (%s/sub) in %s on line %d
bool(false)
