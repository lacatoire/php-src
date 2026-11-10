--TEST--
CURLM_* error constants match the codes known to curl_multi_strerror()
--EXTENSIONS--
curl
--FILE--
<?php
var_dump(CURLM_BAD_SOCKET, CURLM_UNKNOWN_OPTION, CURLM_RECURSIVE_API_CALL, CURLM_ABORTED_BY_CALLBACK);
var_dump(curl_multi_strerror(CURLM_BAD_SOCKET));
var_dump(curl_multi_strerror(CURLM_UNKNOWN_OPTION));
?>
--EXPECT--
int(5)
int(6)
int(8)
int(11)
string(23) "Invalid socket argument"
string(14) "Unknown option"
