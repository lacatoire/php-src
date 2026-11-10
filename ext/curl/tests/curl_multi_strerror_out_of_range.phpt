--TEST--
curl_multi_strerror(): a code outside the int range returns null
--EXTENSIONS--
curl
--FILE--
<?php
var_dump(curl_multi_strerror(4294967296));
var_dump(curl_multi_strerror(PHP_INT_MAX));
var_dump(curl_multi_strerror(PHP_INT_MIN));
var_dump(curl_multi_strerror(0));
?>
--EXPECT--
NULL
NULL
NULL
string(8) "No error"
