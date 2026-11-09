--TEST--
mb_ereg_replace_callback(): callback recompiling the same pattern with other options
--EXTENSIONS--
mbstring
--SKIPIF--
<?php
if (!function_exists('mb_ereg_replace_callback')) die('skip mbregex support not available');
?>
--FILE--
<?php
error_reporting(E_ALL & ~E_DEPRECATED);

$r = mb_ereg_replace_callback("a", function ($m) {
    mb_regex_set_options("i");
    mb_ereg("a", "A");
    mb_regex_set_options("pr");
    return "x";
}, "aaa");
var_dump($r);
?>
--EXPECT--
string(3) "xxx"
