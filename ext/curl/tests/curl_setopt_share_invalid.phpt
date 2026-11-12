--TEST--
curl_setopt(): CURLOPT_SHARE rejects a value that is not a share handle
--EXTENSIONS--
curl
--FILE--
<?php
$ch = curl_init();
foreach (["not an object", null, new stdClass()] as $value) {
    try {
        var_dump(curl_setopt($ch, CURLOPT_SHARE, $value));
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
TypeError: curl_setopt(): The CURLOPT_SHARE option must be a CurlShareHandle or CurlSharePersistentHandle object
TypeError: curl_setopt(): The CURLOPT_SHARE option must be a CurlShareHandle or CurlSharePersistentHandle object
TypeError: curl_setopt(): The CURLOPT_SHARE option must be a CurlShareHandle or CurlSharePersistentHandle object
