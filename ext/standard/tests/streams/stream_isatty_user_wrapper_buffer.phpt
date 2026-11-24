--TEST--
stream_isatty() does not discard data buffered for reading on a user wrapper stream
--FILE--
<?php

class W
{
    public $context;
    private int $pos = 0;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }

    function stream_read($count)
    {
        $data = substr('0123456789abcdefghij', $this->pos, $count);
        $this->pos += strlen($data);
        return $data;
    }

    function stream_eof()
    {
        return $this->pos >= 20;
    }

    function stream_cast($cast_as)
    {
        return $cast_as === STREAM_CAST_FOR_SELECT ? false : fopen(__FILE__, 'r');
    }
}

stream_wrapper_register('ww', 'W');
$f = fopen('ww://x', 'r');
echo fread($f, 3), "\n";
stream_isatty($f);
var_dump(fread($f, 5));

?>
--EXPECT--
012
string(5) "34567"
