--TEST--
msg_set_queue() rejects non-integer and out of range values
--EXTENSIONS--
sysvmsg
--SKIPIF--
<?php
if (!function_exists('posix_getuid')) die('skip posix_getuid() not available');
?>
--FILE--
<?php
$q = msg_get_queue(ftok(__FILE__, 'q'));
$qbytes = msg_stat_queue($q)["msg_qbytes"];
$mode = msg_stat_queue($q)["msg_perm.mode"];

foreach ([
    ["msg_qbytes" => "abc"],
    ["msg_perm.mode" => "rw"],
    ["msg_perm.mode" => null],
    ["msg_perm.mode" => []],
    ["msg_perm.mode" => -1],
    ["msg_perm.uid" => posix_getuid() + 2**32],
    ["msg_perm.gid" => -1],
    ["msg_qbytes" => -1],
] as $data) {
    try {
        var_dump(msg_set_queue($q, $data));
    } catch (Throwable $e) {
        echo get_class($e), ": ", $e->getMessage(), "\n";
    }
}

$stat = msg_stat_queue($q);
var_dump($stat["msg_qbytes"] === $qbytes, $stat["msg_perm.mode"] === $mode);
var_dump(msg_set_queue($q, ["msg_qbytes" => "4096", "msg_perm.mode" => $mode]));
var_dump(msg_stat_queue($q)["msg_qbytes"]);

msg_remove_queue($q);
?>
--EXPECT--
TypeError: msg_set_queue(): Argument #2 ($data) key "msg_qbytes" must be of type int, string given
TypeError: msg_set_queue(): Argument #2 ($data) key "msg_perm.mode" must be of type int, string given
TypeError: msg_set_queue(): Argument #2 ($data) key "msg_perm.mode" must be of type int, null given
TypeError: msg_set_queue(): Argument #2 ($data) key "msg_perm.mode" must be of type int, array given
ValueError: msg_set_queue(): Argument #2 ($data) key "msg_perm.mode" must be between 0 and 65535
ValueError: msg_set_queue(): Argument #2 ($data) key "msg_perm.uid" must be between 0 and 4294967295
ValueError: msg_set_queue(): Argument #2 ($data) key "msg_perm.gid" must be between 0 and 4294967295
ValueError: msg_set_queue(): Argument #2 ($data) key "msg_qbytes" must be between 0 and 9223372036854775807
bool(true)
bool(true)
bool(true)
int(4096)
