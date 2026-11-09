--TEST--
mb_parse_str() with an empty string does not reset mb_http_input()
--EXTENSIONS--
mbstring
--FILE--
<?php
var_dump(mb_parse_str("a=1", $r));
var_dump(mb_http_input());
var_dump(mb_parse_str("", $r));
var_dump($r);
var_dump(mb_http_input());
?>
--EXPECT--
bool(true)
string(5) "UTF-8"
bool(false)
array(0) {
}
string(5) "UTF-8"
