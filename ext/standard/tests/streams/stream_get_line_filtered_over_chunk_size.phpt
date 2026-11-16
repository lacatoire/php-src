--TEST--
stream_get_line() on a filtered stream with a length above the chunk size
--FILE--
<?php

$stream = fopen('php://memory', 'w+');
fwrite($stream, str_repeat('a', 10000) . "\n" . str_repeat('b', 5000));
rewind($stream);
stream_filter_append($stream, 'string.toupper', STREAM_FILTER_READ);

var_dump(strlen(stream_get_line($stream, 20000, "\n")));
var_dump(strlen(stream_get_line($stream, 20000, "\n")));
var_dump(stream_get_line($stream, 20000, "\n"));
var_dump(feof($stream));

?>
--EXPECT--
int(10000)
int(5000)
bool(false)
bool(true)
