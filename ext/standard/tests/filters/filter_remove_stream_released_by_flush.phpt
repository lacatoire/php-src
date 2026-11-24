--TEST--
stream_filter_remove() when the flush drops the last reference to the stream
--FILE--
<?php
class DropsStream extends php_user_filter
{
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        if ($closing) {
            unset($GLOBALS['stream']);
        }

        return PSFS_PASS_ON;
    }
}

stream_filter_register('drops_stream', 'DropsStream');
$stream = fopen('php://memory', 'w+');
$filter = stream_filter_append($stream, 'drops_stream', STREAM_FILTER_WRITE);
fwrite($stream, 'abcd');
var_dump(stream_filter_remove($filter));
echo "done\n";

?>
--EXPECT--
bool(true)
done
