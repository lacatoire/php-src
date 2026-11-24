--TEST--
Read filter changing the chunk size while the read buffer is being filled
--FILE--
<?php

class chunk_size_filter extends php_user_filter
{
    public static $stream;
    private static int $calls = 0;

    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
        }
        if (self::$calls++ === 0) {
            stream_set_chunk_size(self::$stream, 1 << 20);
        }
        return PSFS_FEED_ME;
    }
}

stream_filter_register('chunk_size_filter', 'chunk_size_filter');

$stream = fopen('php://memory', 'w+');
fwrite($stream, str_repeat('A', 300000));
rewind($stream);
chunk_size_filter::$stream = $stream;
stream_filter_append($stream, 'chunk_size_filter', STREAM_FILTER_READ);
var_dump(strlen(fread($stream, 10)));

?>
--EXPECT--
int(0)
