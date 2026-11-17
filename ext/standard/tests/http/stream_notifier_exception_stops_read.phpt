--TEST--
An exception thrown by a stream notification callback stops the read loop
--SKIPIF--
<?php
require 'server.inc';
http_server_skipif();
?>
--INI--
allow_url_fopen=1
--FILE--
<?php
require 'server.inc';

$size = 400000;
$responses = [
    "data://text/plain,HTTP/1.1 200 OK\r\n"
    . "Content-Type: text/plain\r\n"
    . "Content-Length: $size\r\n\r\n"
    . str_repeat('x', $size),
    "data://text/plain,HTTP/1.1 200 OK\r\n"
    . "Content-Type: text/plain\r\n"
    . "Content-Length: $size\r\n\r\n"
    . str_repeat('x', $size),
];
['pid' => $pid, 'uri' => $uri] = http_server($responses);

$createContext = function () {
    $notifications = 0;
    return stream_context_create([], ['notification' => function ($code) use (&$notifications) {
        if ($code === STREAM_NOTIFY_PROGRESS && ++$notifications === 3) {
            throw new Exception('stop');
        }
    }]);
};

$destination = __DIR__ . '/stream_notifier_exception_stops_read.tmp';

try {
    copy($uri, $destination, $createContext());
    echo "no exception\n";
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
var_dump(filesize($destination) < $size);

try {
    file_get_contents($uri, false, $createContext());
    echo "no exception\n";
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

http_server_kill($pid);
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/stream_notifier_exception_stops_read.tmp');
?>
--EXPECT--
Exception: stop
bool(true)
Exception: stop
