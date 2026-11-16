--TEST--
stream_get_contents() does not size its buffer from the stat size of a filtered stream
--INI--
memory_limit=16M
--FILE--
<?php
class drop_all extends php_user_filter
{
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
        }
        return PSFS_FEED_ME;
    }
}
stream_filter_register('drop_all', 'drop_all');

$file = __DIR__ . '/stream_get_contents_read_filter_stat_size.bin';
$handle = fopen($file, 'w');
ftruncate($handle, 64 * 1024 * 1024);
fclose($handle);

$handle = fopen($file, 'r');
stream_filter_append($handle, 'drop_all', STREAM_FILTER_READ);
var_dump(stream_get_contents($handle));
fclose($handle);
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/stream_get_contents_read_filter_stat_size.bin');
?>
--EXPECT--
string(0) ""
