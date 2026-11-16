--TEST--
Appending the consumed filter to a read stream with buffered data keeps the data intact
--FILE--
<?php

$file = __DIR__ . '/consumed_filter_prebuffered_data.tmp';
$content = str_repeat('ABCDEFGHIJ', 3000);
file_put_contents($file, $content);

$stream = fopen($file, 'r');
var_dump(fread($stream, 1));
var_dump(stream_filter_append($stream, 'consumed', STREAM_FILTER_READ) !== false);
$data = stream_get_contents($stream);
var_dump($data !== '' && str_starts_with(substr($content, 1), $data));
fclose($stream);

?>
--CLEAN--
<?php
unlink(__DIR__ . '/consumed_filter_prebuffered_data.tmp');
?>
--EXPECT--
string(1) "A"
bool(true)
bool(true)
