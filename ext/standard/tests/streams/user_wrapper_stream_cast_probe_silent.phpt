--TEST--
User wrapper stream_cast(): a cast probe does not emit warnings for the returned stream
--FILE--
<?php

class W
{
    public $context;
    public static $inner;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }

    function stream_read($count)
    {
        return '';
    }

    function stream_eof()
    {
        return true;
    }

    function stream_cast($cast_as)
    {
        return self::$inner;
    }
}

stream_wrapper_register('ww', 'W');
W::$inner = fopen('php://memory', 'r');
$f = fopen('ww://x', 'r');
var_dump(stream_isatty($f));
var_dump(stream_isatty(W::$inner));

?>
--EXPECT--
bool(false)
bool(false)
