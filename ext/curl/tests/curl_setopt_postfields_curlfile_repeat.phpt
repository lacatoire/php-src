--TEST--
curl_setopt(): repeated CURLOPT_POSTFIELDS with a CURLFile does not grow memory
--EXTENSIONS--
curl
--FILE--
<?php
$ch = curl_init();
$fields = ['file' => new CURLFile(__FILE__)];
for ($i = 0; $i < 100; $i++) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
}
$before = memory_get_usage();
for ($i = 0; $i < 20000; $i++) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
}
var_dump(memory_get_usage() - $before < 100000);
?>
--EXPECT--
bool(true)
