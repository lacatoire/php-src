--TEST--
stream_get_meta_data() reports eof on zlib streams
--EXTENSIONS--
zlib
--FILE--
<?php

$file = __DIR__ . '/zlib_wrapper_meta_data_eof.txt.gz';
$handle = gzopen($file, 'w');
fwrite($handle, 'hello');
fclose($handle);

foreach ([fopen('compress.zlib://' . $file, 'r'), gzopen($file, 'r')] as $stream) {
    var_dump(stream_get_meta_data($stream)['eof']);
    stream_get_contents($stream);
    var_dump(feof($stream), stream_get_meta_data($stream)['eof']);
    fclose($stream);
}

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/zlib_wrapper_meta_data_eof.txt.gz');
?>
--EXPECT--
bool(false)
bool(true)
bool(true)
bool(false)
bool(true)
bool(true)
