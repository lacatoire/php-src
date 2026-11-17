--TEST--
stream_isatty() does not report lost buffered data through a spilled php://temp or a user wrapper cast
--FILE--
<?php
$temp = fopen('php://temp/maxmemory:16', 'w+');
fwrite($temp, str_repeat('x', 100));
rewind($temp);
fread($temp, 1);
var_dump(stream_isatty($temp));
echo ftell($temp), ' ', strlen(stream_get_contents($temp)), "\n";

class Wrapper
{
    public $context;
    public static $inner;

    public function stream_open($path, $mode, $options, &$openedPath)
    {
        return true;
    }

    public function stream_read($count)
    {
        return str_repeat('x', $count);
    }

    public function stream_eof()
    {
        return false;
    }

    public function stream_cast($castAs)
    {
        return self::$inner;
    }
}

Wrapper::$inner = tmpfile();
fwrite(Wrapper::$inner, str_repeat('y', 100));
rewind(Wrapper::$inner);
fread(Wrapper::$inner, 10);

stream_wrapper_register('delegating', 'Wrapper');
$stream = fopen('delegating://x', 'r');
fread($stream, 5);
var_dump(stream_isatty($stream));
?>
--EXPECT--
bool(false)
1 99
bool(false)
