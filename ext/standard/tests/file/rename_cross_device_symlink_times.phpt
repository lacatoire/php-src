--TEST--
rename() across filesystems moves symlinks as links and preserves times
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
if (!is_dir('/dev/shm') || !is_writable('/dev/shm')) die('skip /dev/shm not available');
$d = sys_get_temp_dir() . '/rename_xdev_probe_' . getmypid();
mkdir($d);
$same = stat($d)['dev'] === stat('/dev/shm')['dev'];
rmdir($d);
if ($same) die('skip temp dir and /dev/shm are on the same filesystem');
?>
--FILE--
<?php
$src = __DIR__ . '/rename_cross_device_symlink_times_src';
$dst = '/dev/shm/rename_cross_device_symlink_times_' . getmypid();
mkdir($src);
mkdir($dst);

file_put_contents("$src/target", "content");
symlink("target", "$src/lnk");
var_dump(rename("$src/lnk", "$dst/lnk"));
var_dump(is_link("$dst/lnk"), readlink("$dst/lnk"), file_exists("$src/target"), is_link("$src/lnk"));

file_put_contents("$src/f", "data");
touch("$src/f", 1577934245, 1577934300);
var_dump(rename("$src/f", "$dst/f"));
var_dump(filemtime("$dst/f"), fileatime("$dst/f"));
?>
--CLEAN--
<?php
$src = __DIR__ . '/rename_cross_device_symlink_times_src';
foreach (glob('/dev/shm/rename_cross_device_symlink_times_*') as $d) {
    foreach (glob("$d/*") as $f) { unlink($f); }
    rmdir($d);
}
@unlink("$src/target");
@unlink("$src/lnk");
@unlink("$src/f");
@rmdir($src);
?>
--EXPECT--
bool(true)
bool(true)
string(6) "target"
bool(true)
bool(false)
bool(true)
int(1577934245)
int(1577934300)
