--TEST--
An exception thrown by a stream notification callback stops the http wrapper before it follows a redirect
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

$responses = [
    "data://text/plain,HTTP/1.1 302 Found\r\n"
    . "Location: /second\r\n"
    . "Content-Length: 0\r\n\r\n",
    "data://text/plain,HTTP/1.1 200 OK\r\n"
    . "Content-Length: 0\r\n\r\n",
];
['pid' => $pid, 'uri' => $uri] = http_server($responses, $output);

$context = stream_context_create([], ['notification' => function ($code) {
    if ($code === STREAM_NOTIFY_REDIRECTED) {
        throw new Exception('stop');
    }
}]);

try {
    fopen($uri . '/first', 'r', false, $context);
    echo "no exception\n";
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

http_server_kill($pid);

rewind($output);
var_dump(substr_count(stream_get_contents($output), 'GET /'));
?>
--EXPECT--
Exception: stop
int(1)
