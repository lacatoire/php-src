--TEST--
http wrapper keeps the notification progress of the final response after a redirect
--SKIPIF--
<?php require_once 'server.inc'; http_server_skipif(); ?>
--INI--
allow_url_fopen=1
--FILE--
<?php
require_once 'server.inc';

$responses = [
    "data://text/plain,HTTP/1.0 302 Found\r\nLocation: /target\r\nContent-Length: 50\r\n\r\n" . str_repeat('r', 50),
    "data://text/plain,HTTP/1.0 200 OK\r\nContent-Length: 20000\r\n\r\n" . str_repeat('t', 20000),
];

['pid' => $pid, 'uri' => $uri] = http_server($responses, $output);

$log = [];
$context = stream_context_create([], ['notification' => function ($code, $severity, $message, $messageCode, $bytesSoFar, $bytesMax) use (&$log) {
    if ($code === STREAM_NOTIFY_PROGRESS || $code === STREAM_NOTIFY_COMPLETED) {
        $log[] = [$bytesSoFar, $bytesMax];
    }
}]);

var_dump(strlen(file_get_contents($uri . '/redirect', false, $context)));

$previous = 0;
$decreased = false;
foreach ($log as [$bytesSoFar, $bytesMax]) {
    if ($bytesSoFar < $previous || $bytesMax !== 20000) {
        $decreased = true;
    }
    $previous = $bytesSoFar;
}
var_dump($decreased);
var_dump(end($log));

http_server_kill($pid);
?>
--EXPECT--
int(20000)
bool(false)
array(2) {
  [0]=>
  int(20000)
  [1]=>
  int(20000)
}
