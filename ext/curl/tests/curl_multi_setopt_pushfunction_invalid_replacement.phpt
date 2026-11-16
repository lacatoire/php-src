--TEST--
curl_multi_setopt(): failing to replace CURLMOPT_PUSHFUNCTION does not crash on a server push
--EXTENSIONS--
curl
--SKIPIF--
<?php
include 'skipif-nocaddy.inc';

$curl_version = curl_version();
if ($curl_version['version_number'] < 0x080100) {
    exit("skip: test may crash with curl < 8.1.0");
}
?>
--FILE--
<?php

$mh = curl_multi_init();

curl_multi_setopt($mh, CURLMOPT_PIPELINING, CURLPIPE_MULTIPLEX);
curl_multi_setopt($mh, CURLMOPT_PUSHFUNCTION, function ($parent, $easy, $headers) {
    return CURL_PUSH_OK;
});

try {
    curl_multi_setopt($mh, CURLMOPT_PUSHFUNCTION, 'not a callable');
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://localhost/serverpush");
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_multi_add_handle($mh, $ch);

$active = null;
do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
        curl_multi_select($mh);
    }
} while ($active && $status == CURLM_OK);

echo "done\n";
?>
--EXPECT--
curl_multi_setopt(): Argument #2 ($option) must be a valid callback for option CURLMOPT_PUSHFUNCTION, function "not a callable" not found or invalid function name
done
