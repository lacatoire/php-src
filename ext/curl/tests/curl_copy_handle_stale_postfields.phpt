--TEST--
curl_copy_handle()/clone must not resend a stale POSTFIELDS file after curl_reset() or a later CURLOPT_POSTFIELDS change
--EXTENSIONS--
curl
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$file = __DIR__ . '/curl_copy_handle_stale_postfields.txt';
file_put_contents($file, 'ten bytes!');

$mk = function () use ($file) {
    $h = curl_init();
    curl_setopt_array($h, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => ['f' => new CURLFile($file), 'x' => '1'],
    ]);
    return $h;
};

echo "--- curl_reset() then copy: both must GET, not resend the old file ---\n";
$ch = $mk();
curl_reset($ch);
curl_setopt($ch, CURLOPT_URL, "{$host}/get.inc?test=method");
var_dump(curl_exec($ch));
var_dump(curl_exec(curl_copy_handle($ch)));
var_dump(curl_exec(clone $ch));

echo "--- string CURLOPT_POSTFIELDS then copy: both must post 'a=b', not the old file ---\n";
$ch2 = $mk();
curl_setopt($ch2, CURLOPT_POSTFIELDS, 'a=b');
curl_setopt($ch2, CURLOPT_URL, "{$host}/get.inc?test=post");
var_dump(curl_exec($ch2));
var_dump(curl_exec(curl_copy_handle($ch2)));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/curl_copy_handle_stale_postfields.txt');
?>
--EXPECTF--
--- curl_reset() then copy: both must GET, not resend the old file ---
string(3) "GET"
string(3) "GET"
string(3) "GET"
--- string CURLOPT_POSTFIELDS then copy: both must post 'a=b', not the old file ---
string(%d) "array(1) {
  ["a"]=>
  string(1) "b"
}
"
string(%d) "array(1) {
  ["a"]=>
  string(1) "b"
}
"
