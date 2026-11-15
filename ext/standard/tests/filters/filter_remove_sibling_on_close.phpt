--TEST--
stream_filter_remove() on a sibling filter from onClose() while the stream is being closed
--FILE--
<?php
class RemovesSibling extends php_user_filter
{
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }

        return PSFS_PASS_ON;
    }

    public function onClose(): void
    {
        global $sibling;
        var_dump(stream_filter_remove($sibling));
    }
}

class WritesTail extends php_user_filter
{
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        global $other;
        while ($bucket = stream_bucket_make_writeable($in)) {
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        if ($closing) {
            stream_bucket_append($out, stream_bucket_new($other, 'TAIL'));
        }

        return PSFS_PASS_ON;
    }
}

stream_filter_register('removes_sibling', 'RemovesSibling');
stream_filter_register('writes_tail', 'WritesTail');
$other = fopen('php://memory', 'w+');
$stream = fopen('php://memory', 'w+');
$sibling = stream_filter_prepend($stream, 'writes_tail', STREAM_FILTER_WRITE);
stream_filter_prepend($stream, 'removes_sibling', STREAM_FILTER_WRITE);
fwrite($stream, 'hello');
fclose($stream);
echo "done\n";

?>
--EXPECTF--
Warning: stream_filter_remove(): Cannot remove filter while its stream is being closed in %s on line %d
bool(false)
done
