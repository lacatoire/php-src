--TEST--
stream_get_contents() with a length below 4 * 8192 reads data appended after the end of file
--FILE--
<?php
$file = __DIR__ . '/stream_get_contents_stale_eof.tmp';

foreach ([100, 32767, 32768, null] as $length) {
    file_put_contents($file, 'abc');
    $stream = fopen($file, 'r');
    var_dump(stream_get_contents($stream));
    file_put_contents($file, 'def', FILE_APPEND);
    var_dump(stream_get_contents($stream, $length));
    fclose($stream);
}
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/stream_get_contents_stale_eof.tmp');
?>
--EXPECT--
string(3) "abc"
string(3) "def"
string(3) "abc"
string(3) "def"
string(3) "abc"
string(3) "def"
string(3) "abc"
string(3) "def"
