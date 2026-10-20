--TEST--
curl_getinfo(CURLINFO_TLS_SESSION / CURLINFO_TLS_SSL_PTR) returns false instead of segfaulting
--EXTENSIONS--
curl
--FILE--
<?php
// Neither constant is exposed to PHP, hence the hardcoded values; see
// curl/curl.h: CURLINFO_TLS_SESSION = CURLINFO_PTR + 43, CURLINFO_TLS_SSL_PTR = CURLINFO_PTR + 45.
define('CURLINFO_TLS_SESSION', 4194347);
define('CURLINFO_TLS_SSL_PTR', 4194349);

$ch = curl_init('http://127.0.0.1:1/');
var_dump(curl_getinfo($ch, CURLINFO_TLS_SESSION));
var_dump(curl_getinfo($ch, CURLINFO_TLS_SSL_PTR));
curl_close($ch);
?>
--EXPECT--
bool(false)
bool(false)
