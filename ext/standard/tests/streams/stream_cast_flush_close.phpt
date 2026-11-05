--TEST--
Closing a user stream from stream_flush() while it is cast is refused
--EXTENSIONS--
sockets
--FILE--
<?php

class W
{
    public $context;
    public static $stream;

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }

    public function stream_write($data)
    {
        return strlen($data);
    }

    public function stream_flush()
    {
        var_dump(fclose(self::$stream));
        return true;
    }

    public function stream_cast($as)
    {
        return false;
    }
}

stream_wrapper_register('wx', 'W');
W::$stream = fopen('wx://x', 'w');
fwrite(W::$stream, 'data');
var_dump(socket_import_stream(W::$stream));

?>
--EXPECTF--
Warning: fclose(): cannot close the provided stream, as it must not be manually closed in %s on line %d
bool(false)

Warning: socket_import_stream(): Cannot represent a stream of type user-space as a Socket Descriptor in %s on line %d
bool(false)
