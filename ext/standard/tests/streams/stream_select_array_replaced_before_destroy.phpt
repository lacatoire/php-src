--TEST--
stream_select(): by-reference array is replaced before the old one is destroyed
--FILE--
<?php

class Wrapper
{
    public $context;
    private string $name;

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->name = $path;
        return true;
    }

    public function stream_read($count)
    {
        return "data";
    }

    public function stream_eof()
    {
        return false;
    }

    public function stream_close()
    {
        if ($this->name === 'wrapper://dropped') {
            echo "Close: ", count($GLOBALS['read']), " element(s)\n";
        }
    }
}

stream_wrapper_register('wrapper', 'Wrapper');

[$socket, $peer] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
$buffered = fopen('wrapper://buffered', 'r');
fread($buffered, 1);

$read = [$buffered, fopen('wrapper://dropped', 'r'), $socket];
$write = null;
$except = null;
var_dump(@stream_select($read, $write, $except, 0));
echo "Result: ", count($read), " element(s)\n";

?>
--EXPECT--
Close: 1 element(s)
int(1)
Result: 1 element(s)
