--TEST--
iconv_strpos(), iconv_strrpos(): overlapping and partial matches
--EXTENSIONS--
iconv
--FILE--
<?php
var_dump(iconv_strrpos("aaa", "aa", "UTF-8"));
var_dump(iconv_strrpos("xaaay", "aa", "UTF-8"));
var_dump(iconv_strrpos("aaaaaaaé", "aaa", "UTF-8"));
var_dump(iconv_strpos("bbaababbabab", "baba", 0, "UTF-8"));
var_dump(iconv_strpos("abaababaaa", "abab", 0, "UTF-8"));
?>
--EXPECT--
int(1)
int(2)
int(4)
int(7)
int(3)
