--TEST--
stream_isatty() returns false when the wrapper's stream_cast() fails after the probe succeeded
--FILE--
<?php

class W
{
    public $context;
    private static int $calls = 0;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }

    function stream_cast($cast_as)
    {
        // Succeed for the probe, fail for the real cast
        return (self::$calls++ % 2 === 0) ? fopen(__FILE__, 'r') : false;
    }

    function stream_read($count)
    {
        return '';
    }

    function stream_eof()
    {
        return true;
    }
}

stream_wrapper_register('ww', 'W');
$f = fopen('ww://x', 'r');
for ($i = 0; $i < 3; $i++) {
    var_dump(stream_isatty($f));
}

?>
--EXPECT--
bool(false)
bool(false)
bool(false)
