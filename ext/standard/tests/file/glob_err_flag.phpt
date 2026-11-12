--TEST--
glob(): GLOB_ERR must abort on a directory read error
--SKIPIF--
<?php
if (substr(PHP_OS, 0, 3) == 'WIN') die('skip no windows support');
require __DIR__ . '/../skipif_root.inc';
?>
--FILE--
<?php
echo "*** Testing glob() : GLOB_ERR ***\n";

$dirname = __DIR__ . "/glob_err_flag_unreadable";
mkdir($dirname, 0000);

// Without GLOB_ERR, an unreadable directory is treated as "no matches".
var_dump(glob("$dirname/*"));
// With GLOB_ERR, php_glob() must report the read error instead of
// silently returning an empty array.
var_dump(glob("$dirname/*", GLOB_ERR));

echo "Done\n";
?>
--CLEAN--
<?php
$dirname = __DIR__ . "/glob_err_flag_unreadable";
chmod($dirname, 0755);
rmdir($dirname);
?>
--EXPECT--
*** Testing glob() : GLOB_ERR ***
array(0) {
}
bool(false)
Done
