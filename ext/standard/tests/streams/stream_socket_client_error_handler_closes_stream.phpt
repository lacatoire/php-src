--TEST--
stream_socket_client(): an error handler cannot close the stream being connected
--SKIPIF--
<?php
if (!in_array('unix', stream_get_transports())) {
    die('skip unix sockets not available');
}
?>
--FILE--
<?php

set_error_handler(function () {
    foreach (get_resources('stream') as $id => $stream) {
        if ($id > 3) {
            var_dump(@fclose($stream));
        }
    }
    return true;
});

var_dump(stream_socket_client('unix:///nonexistent-directory/' . str_repeat('a', 120)));

?>
--EXPECT--
bool(false)
bool(false)
