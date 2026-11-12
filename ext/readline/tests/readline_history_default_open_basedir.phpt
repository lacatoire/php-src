--TEST--
readline_read_history()/readline_write_history(): default ~/.history is checked against open_basedir
--EXTENSIONS--
readline
--SKIPIF--
<?php
if (getenv('TEST_PHP_EXECUTABLE') === false) die('skip no executable');
if (READLINE_LIB === 'libedit') die('skip GNU readline only');
?>
--FILE--
<?php

$home = sys_get_temp_dir() . '/readline_default_history_' . getmypid();
$allowed = $home . '/allowed';
mkdir($allowed, 0777, true);
file_put_contents($home . '/.history', "secret line\n");

putenv("HOME=$home");
$php = getenv('TEST_PHP_EXECUTABLE');
$code = 'readline_add_history("appended"); var_dump(readline_read_history()); var_dump(readline_list_history()); var_dump(readline_write_history());';
passthru(escapeshellarg($php) . ' -n -d extension_dir=' . escapeshellarg(ini_get('extension_dir')) . ' -d open_basedir=' . escapeshellarg($allowed) . ' -r ' . escapeshellarg($code) . ' 2>&1');
var_dump(file_get_contents($home . '/.history'));

?>
--CLEAN--
<?php
$home = sys_get_temp_dir() . '/readline_default_history_' . getmypid();
@unlink($home . '/.history');
@rmdir($home . '/allowed');
@rmdir($home);
?>
--EXPECTF--
Warning: readline_read_history(): open_basedir restriction in effect. File(%s/.history) is not within the allowed path(s): (%s) in %s on line %d
bool(false)
array(1) {
  [0]=>
  string(8) "appended"
}

Warning: readline_write_history(): open_basedir restriction in effect. File(%s/.history) is not within the allowed path(s): (%s) in %s on line %d
bool(false)
string(12) "secret line
"
