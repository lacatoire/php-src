--TEST--
inflate_add(): an empty chunk after the end of the stream keeps its status
--EXTENSIONS--
zlib
--FILE--
<?php
$ctx = inflate_init(ZLIB_ENCODING_DEFLATE);
var_dump(inflate_add($ctx, zlib_encode('first-stream', ZLIB_ENCODING_DEFLATE)));
var_dump(inflate_get_status($ctx) === ZLIB_STREAM_END, inflate_get_read_len($ctx));
var_dump(inflate_add($ctx, ''));
var_dump(inflate_get_status($ctx) === ZLIB_STREAM_END, inflate_get_read_len($ctx));
var_dump(inflate_add($ctx, '', ZLIB_FINISH));
var_dump(inflate_get_status($ctx) === ZLIB_STREAM_END, inflate_get_read_len($ctx));

// A new stream with data still starts afresh
var_dump(inflate_add($ctx, zlib_encode('second', ZLIB_ENCODING_DEFLATE)));
var_dump(inflate_get_status($ctx) === ZLIB_STREAM_END, inflate_get_read_len($ctx));
?>
--EXPECT--
string(12) "first-stream"
bool(true)
int(20)
string(0) ""
bool(true)
int(20)
string(0) ""
bool(true)
int(20)
string(6) "second"
bool(true)
int(14)
