--TEST--
socket_select(): the variables are changed by a close handler or an error handler
--EXTENSIONS--
sockets
--FILE--
<?php

class Closer extends php_user_filter
{
    public function filter($in, $out, &$consumed, bool $closing): int
    {
        return PSFS_PASS_ON;
    }

    public function onClose(): void
    {
        global $write;
        $write = [1, 2, 3];
    }
}
stream_filter_register('closer', 'Closer');

function idle_sockets(): array
{
    socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
    $GLOBALS['peer'] = $pair[1];
    $stream = socket_export_stream($pair[0]);
    stream_filter_append($stream, 'closer', STREAM_FILTER_READ);
    unset($stream);
    return [$pair[0]];
}

echo "Close handler\n";
$read = idle_sockets();
socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $ready);
socket_write($ready[1], 'x');
$read[] = $ready[0];

socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $other);
$write = [$other[0]];
$except = null;
var_dump(socket_select($read, $write, $except, 0));
var_dump(count($read), $write);

echo "Error handler\n";
socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
socket_write($pair[1], 'x');

$read = [$pair[0]];
$write = [$pair[1]];
$except = null;
$last = &$read;

set_error_handler(function (int $no, string $message) use (&$last) {
    $last = $message;
    return true;
});

var_dump(socket_select($read, $write, $except, 0.5));
var_dump($read);
var_dump(count($write));

?>
--EXPECT--
Close handler
int(2)
int(1)
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
}
Error handler
int(1)
string(57) "Implicit conversion from float 0.5 to int loses precision"
int(1)
