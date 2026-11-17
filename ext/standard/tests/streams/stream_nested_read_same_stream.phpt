--TEST--
A read of a stream from the read operation of its own user wrapper is refused
--FILE--
<?php
class Wrapper
{
    public $context;
    public static int $calls = 0;

    public function stream_open($path, $mode, $options, &$openedPath)
    {
        return true;
    }

    public function stream_read($count)
    {
        global $stream;
        if (self::$calls++ === 0) {
            var_dump(fread($stream, 8192));
        }
        return str_repeat('B', min($count, 8192));
    }

    public function stream_eof()
    {
        return self::$calls > 2;
    }
}

stream_wrapper_register('nested', 'Wrapper');
$stream = fopen('nested://x', 'r');
$data = stream_get_contents($stream, 8192);
var_dump(strlen($data), strlen(str_replace('B', '', $data)));

Wrapper::$calls = 1;
var_dump(stream_get_line($stream, 100, "\n") === str_repeat('B', 100));
?>
--EXPECTF--
Warning: fread(): Cannot read from a stream while a read is in progress on it in %s on line %d
bool(false)
int(8192)
int(0)
bool(true)
