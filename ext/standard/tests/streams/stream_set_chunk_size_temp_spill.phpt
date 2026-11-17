--TEST--
stream_set_chunk_size() on php://temp is kept when the stream spills to a file
--FILE--
<?php
class CountingFilter extends php_user_filter
{
    public static int $buckets = 0;

    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            self::$buckets++;
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}

$temp = fopen('php://temp/maxmemory:10', 'r+');
var_dump(stream_set_chunk_size($temp, 77), stream_set_chunk_size($temp, 78));
fwrite($temp, str_repeat('x', 50));
var_dump(stream_set_chunk_size($temp, 79), stream_set_chunk_size($temp, 80));

stream_filter_register('counting', 'CountingFilter');
$memory = fopen('php://temp', 'r+');
fwrite($memory, str_repeat('x', 100));
rewind($memory);
stream_set_chunk_size($memory, 10);
stream_filter_append($memory, 'counting', STREAM_FILTER_READ);
var_dump(strlen(stream_get_contents($memory)));
var_dump(CountingFilter::$buckets);
?>
--EXPECT--
int(8192)
int(77)
int(78)
int(79)
int(100)
int(10)
