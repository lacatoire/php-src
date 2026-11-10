--TEST--
mb_ereg_replace(): empty matches are replaced once and do not split multibyte characters
--EXTENSIONS--
mbstring
--SKIPIF--
<?php
if (!function_exists('mb_ereg_replace')) die('skip mbregex support not available');
?>
--FILE--
<?php
error_reporting(E_ALL & ~E_DEPRECATED);
mb_regex_encoding('UTF-8');

var_dump(mb_ereg_replace('$', '-', 'ab'));
var_dump(mb_ereg_replace('\b', '-', 'ab cd'));
var_dump(mb_ereg_replace('(?=c)', '-', 'abc'));
var_dump(mb_ereg_replace('', '-', '日本'));
var_dump(mb_ereg_replace('本?', 'X', '日本日'));
var_dump(mb_ereg_replace('x?', '-', 'abc'));
var_dump(mb_ereg_replace('a*', '-', 'baac'));

$count = 0;
mb_ereg_replace_callback('\b', function ($m) use (&$count) {
    $count++;
    return '-';
}, 'ab cd');
var_dump($count);

mb_regex_encoding('EUC-JP');
$str = mb_convert_encoding('日本', 'EUC-JP', 'UTF-8');
var_dump(bin2hex(mb_ereg_replace('', '-', $str)));
?>
--EXPECT--
string(3) "ab-"
string(9) "-ab- -cd-"
string(4) "ab-c"
string(9) "-日-本-"
string(10) "X日XX日X"
string(7) "-a-b-c-"
string(6) "-b--c-"
int(4)
string(14) "2dc6fc2dcbdc2d"
