--TEST--
dba_close(): a persistent connection is really closed
--EXTENSIONS--
dba
--SKIPIF--
<?php
require_once __DIR__ . '/setup/setup_dba_tests.inc';
check_skip('flatfile');
if (!is_dir('/proc/self/fd')) die('skip needs /proc/self/fd');
?>
--FILE--
<?php
function fds() { return count(glob('/proc/self/fd/*')); }
$dir = __DIR__ . '/dba_close_persistent_dir';
mkdir($dir);

$before = fds();
for ($i = 0; $i < 10; $i++) {
    $db = dba_popen("$dir/t$i.db", "c", "flatfile");
    dba_insert("k", "v", $db);
    dba_close($db);
}
echo "persistent leaked ", fds() - $before, "\n";

$before = fds();
for ($i = 0; $i < 10; $i++) {
    $db = dba_open("$dir/o$i.db", "c", "flatfile");
    dba_close($db);
}
echo "non persistent leaked ", fds() - $before, "\n";

// Reopening gives a fresh handle on the same data
$db = dba_popen("$dir/t0.db", "c", "flatfile");
var_dump(dba_fetch("k", $db));
dba_close($db);
?>
--CLEAN--
<?php
$dir = __DIR__ . '/dba_close_persistent_dir';
foreach (glob("$dir/*") as $f) {
    unlink($f);
}
@rmdir($dir);
?>
--EXPECT--
persistent leaked 0
non persistent leaked 0
string(1) "v"
