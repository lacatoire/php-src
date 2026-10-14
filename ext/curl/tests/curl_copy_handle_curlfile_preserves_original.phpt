--TEST--
curl_copy_handle()/clone after a CURLFile POST must not break the original's next curl_exec()
--EXTENSIONS--
curl
--FILE--
<?php
include 'server.inc';
$host = curl_cli_server_start();

$file = __DIR__ . '/curl_copy_handle_curlfile_preserves_original.txt';
file_put_contents($file, str_repeat('x', 2000));

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "{$host}/get.inc?test=file",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => ['file' => new CURLFile($file)],
]);

echo "--- first exec on the original ---\n";
var_dump(curl_exec($ch));
var_dump(curl_errno($ch));

$copy = clone $ch;

echo "--- second exec on the original, after cloning ---\n";
var_dump(curl_exec($ch));
var_dump(curl_errno($ch));

curl_close($copy);
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/curl_copy_handle_curlfile_preserves_original.txt');
?>
--EXPECTF--
--- first exec on the original ---
string(%d) "curl_copy_handle_curlfile_preserves_original.txt|application/octet-stream|2000"
int(0)
--- second exec on the original, after cloning ---
string(%d) "curl_copy_handle_curlfile_preserves_original.txt|application/octet-stream|2000"
int(0)
