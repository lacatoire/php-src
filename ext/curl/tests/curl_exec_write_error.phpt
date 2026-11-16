--TEST--
curl_exec() reports the final fflush() failure on CURLOPT_FILE instead of returning true
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== "Linux") die("skip /dev/full is only reliably available on Linux");
if (!file_exists('/dev/full')) die("skip /dev/full not available");
?>
--EXTENSIONS--
curl
--FILE--
<?php
$body_file = tempnam(sys_get_temp_dir(), 'php-curl-test');
file_put_contents($body_file, "short body\n");

$fp = fopen('/dev/full', 'w');

$ch = curl_init();
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_URL, 'file://' . $body_file);
$result = curl_exec($ch);

var_dump($result);
var_dump(curl_errno($ch) === CURLE_WRITE_ERROR);

fclose($fp);
unlink($body_file);
?>
--EXPECT--
bool(false)
bool(true)
