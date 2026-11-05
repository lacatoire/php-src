--TEST--
User stream wrapper: closing the stream from stream_eof() is refused
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

    public function stream_read($count)
    {
        return '';
    }

    public function stream_eof()
    {
        var_dump(fclose(self::$stream));
        return true;
    }
}

stream_wrapper_register('wx', 'W');

W::$stream = fopen('wx://x', 'r');
var_dump(stream_get_meta_data(W::$stream)['eof']);
fclose(W::$stream);

W::$stream = fopen('wx://x', 'r');
var_dump(feof(W::$stream));
fclose(W::$stream);

?>
--EXPECTF--
Warning: fclose(): cannot close the provided stream, as it must not be manually closed in %s on line %d
bool(false)
bool(true)

Warning: fclose(): cannot close the provided stream, as it must not be manually closed in %s on line %d
bool(false)
bool(true)
