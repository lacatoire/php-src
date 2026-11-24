--TEST--
The stream returned by stream_cast() stays open while the caller uses its descriptor
--FILE--
<?php
class Wrapper
{
    public $context;

    public function stream_open($path, $mode, $options, &$openedPath)
    {
        return true;
    }

    public function stream_read($count)
    {
        return '';
    }

    public function stream_eof()
    {
        return true;
    }

    public function stream_cast($castAs)
    {
        return tmpfile();
    }
}

stream_wrapper_register('cast', 'Wrapper');
$stream = fopen('cast://x', 'r');

$read = [$stream];
$write = null;
$except = null;
var_dump(stream_select($read, $write, $except, 0));
fclose($stream);
?>
--EXPECT--
int(1)
