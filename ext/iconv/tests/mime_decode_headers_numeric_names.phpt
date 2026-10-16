--TEST--
iconv_mime_decode_headers(): repeated integer-like header names are collected
--EXTENSIONS--
iconv
--FILE--
<?php
var_dump(iconv_mime_decode_headers("123: a\r\n123: b\r\nX: 1\r\nX: 2"));
var_dump(iconv_mime_decode_headers("0: a\n0: b\n-5: c\n-5: d"));
?>
--EXPECT--
array(2) {
  [123]=>
  array(2) {
    [0]=>
    string(1) "a"
    [1]=>
    string(1) "b"
  }
  ["X"]=>
  array(2) {
    [0]=>
    string(1) "1"
    [1]=>
    string(1) "2"
  }
}
array(2) {
  [0]=>
  array(2) {
    [0]=>
    string(1) "a"
    [1]=>
    string(1) "b"
  }
  [-5]=>
  array(2) {
    [0]=>
    string(1) "c"
    [1]=>
    string(1) "d"
  }
}
