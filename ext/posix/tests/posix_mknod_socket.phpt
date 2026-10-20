--TEST--
posix_mknod(): socket nodes do not require a major number
--EXTENSIONS--
posix
--SKIPIF--
<?php
if (!function_exists('posix_mknod')) die('skip posix_mknod() not found');
if (!defined('POSIX_S_IFSOCK')) die('skip POSIX_S_IFSOCK not available');
?>
--FILE--
<?php

$path = __DIR__ . '/posix_mknod_socket.sock';

var_dump(posix_mknod($path, POSIX_S_IFSOCK | 0644));
var_dump(filetype($path));

try {
    posix_mknod($path . '2', POSIX_S_IFCHR | 0644);
} catch (ValueError $e) {
    echo $e->getMessage(), "\n";
}

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/posix_mknod_socket.sock');
?>
--EXPECT--
bool(true)
string(6) "socket"
posix_mknod(): Argument #3 ($major) cannot be 0 for the POSIX_S_IFCHR and POSIX_S_IFBLK modes
