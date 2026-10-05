--TEST--
mkdir() rejects permissions outside the mode range instead of wrapping them
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
?>
--FILE--
<?php
$dir = __DIR__ . '/mkdir_permissions_out_of_range';

foreach ([-1, 4294967296, 4294967296 + 0755, PHP_INT_MAX, PHP_INT_MIN] as $mode) {
    try {
        mkdir($dir, $mode);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
    var_dump(file_exists($dir));
}

umask(0);
var_dump(mkdir($dir, 0755));
printf("%o\n", fileperms($dir) & 07777);
?>
--CLEAN--
<?php
@rmdir(__DIR__ . '/mkdir_permissions_out_of_range');
?>
--EXPECT--
mkdir(): Argument #2 ($permissions) must be between 0 and 4294967295
bool(false)
mkdir(): Argument #2 ($permissions) must be between 0 and 4294967295
bool(false)
mkdir(): Argument #2 ($permissions) must be between 0 and 4294967295
bool(false)
mkdir(): Argument #2 ($permissions) must be between 0 and 4294967295
bool(false)
mkdir(): Argument #2 ($permissions) must be between 0 and 4294967295
bool(false)
bool(true)
755
