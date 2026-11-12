--TEST--
str_getcsv() and basename() with a locale whose characters can leave a pending conversion state
--SKIPIF--
<?php
if (setlocale(LC_CTYPE, 'zh_HK.BIG5-HKSCS') === false) {
    die('skip zh_HK.BIG5-HKSCS locale not available');
}
if (setlocale(LC_CTYPE, 'vi_VN.CP1258') === false) {
    die('skip vi_VN.CP1258 locale not available');
}
?>
--FILE--
<?php
setlocale(LC_CTYPE, 'zh_HK.BIG5-HKSCS');
var_dump(count(str_getcsv("\x88\x62,b", ',', '"', '')));
var_dump(basename("x/\x88\x62") === "\x88\x62");
var_dump(basename("dir/foo"));

setlocale(LC_CTYPE, 'vi_VN.CP1258');
var_dump(str_getcsv('p,q', ',', '"', ''));
?>
--EXPECT--
int(2)
bool(true)
string(3) "foo"
array(2) {
  [0]=>
  string(1) "p"
  [1]=>
  string(1) "q"
}
