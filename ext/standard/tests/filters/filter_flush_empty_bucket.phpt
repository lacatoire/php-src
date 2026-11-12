--TEST--
Empty buckets passed on during a filter flush do not stay linked to its brigade
--FILE--
<?php
class KeepBucketFilter extends php_user_filter
{
    public static $bucket = null;

    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
        }
        if (self::$bucket === null) {
            self::$bucket = stream_bucket_new($this->stream, "");
        }
        stream_bucket_append($out, self::$bucket);

        return PSFS_PASS_ON;
    }
}

stream_filter_register("keep_bucket", "KeepBucketFilter");

$fp = fopen("php://memory", "w+");
$filter = stream_filter_append($fp, "keep_bucket", STREAM_FILTER_WRITE);
/* The flush passes on an empty bucket that the filter class still references */
stream_filter_remove($filter);

stream_filter_append($fp, "keep_bucket", STREAM_FILTER_WRITE);
var_dump(fwrite($fp, "x"));
fclose($fp);
?>
Done
--EXPECT--
int(1)
Done
