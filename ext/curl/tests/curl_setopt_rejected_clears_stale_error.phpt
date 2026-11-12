--TEST--
A rejected curl_setopt() must not leave curl_error() reporting a stale message from an earlier curl_exec()
--EXTENSIONS--
curl
--FILE--
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:1/');
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
curl_exec($ch);

var_dump(curl_errno($ch) !== 0);
var_dump(curl_error($ch) !== '');

var_dump(curl_setopt($ch, CURLOPT_HTTP_VERSION, 12345));

// The old connect-failure message must be gone: curl_error() must now
// match curl_easy_strerror() for the *current* errno, not the previous one.
var_dump(curl_error($ch) === curl_strerror(curl_errno($ch)));
?>
--EXPECT--
bool(true)
bool(true)
bool(false)
bool(true)
