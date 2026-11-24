--TEST--
stream_select() reports a buffered stream that has no selectable descriptor
--FILE--
<?php
class Wrapper
{
    public $context;
    private $position = 0;

    public function stream_open($path, $mode, $options, &$openedPath)
    {
        return true;
    }

    public function stream_read($count)
    {
        return $this->position++ ? '' : 'hello';
    }

    public function stream_eof()
    {
        return $this->position > 0;
    }
}

stream_wrapper_register('buffered', 'Wrapper');
$stream = fopen('buffered://x', 'r');
fread($stream, 1);

$read = [$stream];
$write = null;
$except = null;
try {
    var_dump(stream_select($read, $write, $except, 0));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump(count($read));

$other = fopen('buffered://y', 'r');
$read = [$other];
try {
    var_dump(stream_select($read, $write, $except, 0));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--EXPECTF--
Warning: stream_select(): Wrapper::stream_cast is not implemented! in %s on line %d

Warning: stream_select(): Cannot represent a stream of type user-space as a select()able descriptor in %s on line %d
int(1)
int(1)

Warning: stream_select(): Wrapper::stream_cast is not implemented! in %s on line %d

Warning: stream_select(): Cannot represent a stream of type user-space as a select()able descriptor in %s on line %d
ValueError: No stream arrays were passed
