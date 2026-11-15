--TEST--
http wrapper notification progress does not carry over to the next request on the same context
--SKIPIF--
<?php require_once 'server.inc'; http_server_skipif(); ?>
--INI--
allow_url_fopen=1
--FILE--
<?php
require_once 'server.inc';

$response = "data://text/plain,HTTP/1.0 200 OK\r\nContent-Length: 20000\r\n\r\n" . str_repeat('t', 20000);

['pid' => $pid, 'uri' => $uri] = http_server([$response, $response], $output);

$log = [];
$context = stream_context_create([], ['notification' => function ($code, $severity, $message, $messageCode, $bytesSoFar, $bytesMax) use (&$log) {
    if ($code === STREAM_NOTIFY_PROGRESS || $code === STREAM_NOTIFY_COMPLETED) {
        $log[] = "$bytesSoFar/$bytesMax";
    }
}]);

foreach ([1, 2] as $request) {
    $log = [];
    var_dump(strlen(file_get_contents($uri . '/target', false, $context)));
    echo "first: ", $log[0], ", last: ", end($log), "\n";
}

http_server_kill($pid);
?>
--EXPECT--
int(20000)
first: 0/20000, last: 20000/20000
int(20000)
first: 0/20000, last: 20000/20000
