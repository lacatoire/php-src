--TEST--
CURLSHE_* constants match the codes known to curl_share_strerror()
--EXTENSIONS--
curl
--FILE--
<?php
var_dump(CURLSHE_OK, CURLSHE_BAD_OPTION, CURLSHE_IN_USE, CURLSHE_INVALID, CURLSHE_NOMEM, CURLSHE_NOT_BUILT_IN);
var_dump(curl_share_strerror(CURLSHE_OK));
var_dump(curl_share_strerror(CURLSHE_BAD_OPTION));
?>
--EXPECT--
int(0)
int(1)
int(2)
int(3)
int(4)
int(5)
string(8) "No error"
string(20) "Unknown share option"
