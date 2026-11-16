--TEST--
curl_setopt(): an unconvertible element in CURLOPT_HTTPHEADER keeps the previous list
--EXTENSIONS--
curl
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$ch = curl_init("{$host}/get.inc");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Original: keep-me"]);

try {
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Good: yes", new stdClass()]);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

curl_exec($ch);
$sent = curl_getinfo($ch, CURLINFO_HEADER_OUT);
var_dump(str_contains($sent, 'X-Original: keep-me'));
var_dump(str_contains($sent, 'X-Good: yes'));
?>
--EXPECT--
Error: Object of class stdClass could not be converted to string
bool(true)
bool(false)
