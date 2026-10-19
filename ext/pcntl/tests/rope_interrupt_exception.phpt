--TEST--
A signal handler throwing before a rope is initialised in an error handler
--EXTENSIONS--
pcntl
--FILE--
<?php
pcntl_async_signals(true);
pcntl_signal(SIGALRM, function () { throw new Exception('sig'); });
set_error_handler(function ($n, $s) { echo "H: $s $n and more\n"; return true; });
$srv = stream_socket_server('tcp://127.0.0.1:0');
pcntl_alarm(1);
try {
    stream_socket_accept($srv, 3);
} catch (Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
}
echo "end\n";
?>
--EXPECT--
Exception: sig
end
