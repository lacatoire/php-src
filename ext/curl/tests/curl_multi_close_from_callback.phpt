--TEST--
curl_multi_close() called from a write callback of one of its own handles during curl_multi_exec()
--EXTENSIONS--
curl
--FILE--
<?php
$payload = __DIR__ . DIRECTORY_SEPARATOR . 'curl_testdata1.txt';

$mh = curl_multi_init();
$ch = curl_init('file://' . $payload);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($h, $d) use ($mh) {
    try {
        curl_multi_close($mh);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
    return strlen($d);
});
curl_multi_add_handle($mh, $ch);

$running = null;
do {
    curl_multi_exec($mh, $running);
} while ($running);

curl_multi_remove_handle($mh, $ch);
curl_multi_close($mh);

echo "done\n";
?>
--EXPECT--
Error: curl_multi_close(): Attempt to close a multi handle from a callback of one of its cURL handles
done
