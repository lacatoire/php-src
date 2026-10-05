--TEST--
mb_ereg(), mb_eregi() and mb_ereg_match() warn when the retry limit is hit
--EXTENSIONS--
mbstring
--SKIPIF--
<?php
if (!function_exists('mb_ereg')) die('skip mb_ereg not available');
if (@version_compare(MB_ONIGURUMA_VERSION, '6.9.3') < 0) {
    die('skip requires Oniguruma >= 6.9.3');
}
?>
--FILE--
<?php

error_reporting(E_ALL & ~E_DEPRECATED);
$s = str_repeat("a", 26) . "c";
$p = "^(a+)+$|^a{26}c$";
var_dump(mb_ereg_match($p, $s));
var_dump(mb_ereg($p, $s));
var_dump(mb_eregi($p, $s));
echo "Plain mismatches stay silent\n";
var_dump(mb_ereg_match("^b", "abc"));
var_dump(mb_ereg("b", "aaa"));
var_dump(mb_eregi("b", "aaa"));

?>
--EXPECTF--
Warning: mb_ereg_match(): mbregex match failure in mb_ereg_match(): retry-limit-in-match over in %s on line %d
bool(false)

Warning: mb_ereg(): mbregex search failure in mbregex_exec(): retry-limit-in-match over in %s on line %d
bool(false)

Warning: mb_eregi(): mbregex search failure in mbregex_exec(): retry-limit-in-match over in %s on line %d
bool(false)
Plain mismatches stay silent
bool(false)
bool(false)
bool(false)
