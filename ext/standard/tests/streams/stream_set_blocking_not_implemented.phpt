--TEST--
stream_set_blocking() returns false on streams without a blocking mode
--FILE--
<?php

foreach (['php://memory', 'php://temp', 'php://output'] as $url) {
    $stream = fopen($url, 'r+');
    var_dump(stream_set_blocking($stream, false));
}

$stream = fopen('php://temp', 'r+');
var_dump(stream_set_timeout($stream, 1));

?>
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
