--TEST--
stream_filter_append() on buffered data with a filter that grows the read buffer of its stream
--FILE--
<?php

$file = __DIR__ . '/filter_append_prebuffered_nested_read.txt';
file_put_contents($file, str_repeat('A', 40000));

class NestedReadFilter extends php_user_filter
{
    public static bool $done = false;

    public function filter($in, $out, &$consumed, bool $closing): int
    {
        if (!self::$done) {
            self::$done = true;
            stream_set_chunk_size($this->stream, 65536);
            fread($this->stream, 20000);
        }
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}

class GrowFilter extends php_user_filter
{
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
            $bucket->data = str_repeat($bucket->data, 300);
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}

class NestedAppendFilter extends php_user_filter
{
    public static bool $done = false;

    public function filter($in, $out, &$consumed, bool $closing): int
    {
        if (!self::$done) {
            self::$done = true;
            stream_filter_append($this->stream, 'grow', STREAM_FILTER_READ);
        }
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }
}

stream_filter_register('nested_read', 'NestedReadFilter');
stream_filter_register('grow', 'GrowFilter');
stream_filter_register('nested_append', 'NestedAppendFilter');

$fp = fopen($file, 'r');
fread($fp, 10);
stream_filter_append($fp, 'nested_read', STREAM_FILTER_READ);
$data = fread($fp, 8192);
var_dump(strlen($data), trim($data, 'A'));
fclose($fp);

$fp = fopen($file, 'r');
fgetc($fp);
stream_filter_append($fp, 'nested_append', STREAM_FILTER_READ);
$data = fread($fp, 8192);
var_dump(strlen($data), trim($data, 'A'));
fclose($fp);

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/filter_append_prebuffered_nested_read.txt');
?>
--EXPECT--
int(8182)
string(0) ""
int(8192)
string(0) ""
