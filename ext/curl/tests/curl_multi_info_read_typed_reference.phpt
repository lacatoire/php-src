--TEST--
curl_multi_info_read(): a typed reference that rejects an int does not lose the message
--EXTENSIONS--
curl
--FILE--
<?php

class Box
{
    public array $queued = [];
}

$file = __DIR__ . '/curl_multi_info_read_typed_reference.txt';
file_put_contents($file, 'data');

$mh = curl_multi_init();
$ch = curl_init('file://' . $file);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_multi_add_handle($mh, $ch);

do {
    curl_multi_exec($mh, $running);
} while ($running);

$box = new Box();
try {
    curl_multi_info_read($mh, $box->queued);
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}

$info = curl_multi_info_read($mh, $queued);
var_dump($info['msg'] === CURLMSG_DONE, $info['result'], $queued);

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/curl_multi_info_read_typed_reference.txt');
?>
--EXPECT--
Cannot assign int to reference held by property Box::$queued of type array
bool(true)
int(0)
int(0)
