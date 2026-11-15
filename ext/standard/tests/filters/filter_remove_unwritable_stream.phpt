--TEST--
stream_filter_remove() of a write filter that emits data on a stream without a write handler
--FILE--
<?php
class WritesTail extends php_user_filter
{
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        if ($closing) {
            stream_bucket_append($out, stream_bucket_new($this->stream, 'TAIL'));
        }

        return PSFS_PASS_ON;
    }
}

stream_filter_register('writes_tail', 'WritesTail');
$stream = fopen('data://text/plain,abc', 'r');
$filter = stream_filter_prepend($stream, 'writes_tail', STREAM_FILTER_WRITE);
var_dump(stream_filter_remove($filter));

$dir = opendir(__DIR__);
$filter = stream_filter_append($dir, 'writes_tail', STREAM_FILTER_WRITE);
var_dump(stream_filter_remove($filter));

?>
--EXPECT--
bool(true)
bool(true)
