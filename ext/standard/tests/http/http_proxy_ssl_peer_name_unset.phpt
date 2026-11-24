--TEST--
HTTP proxy SSL CONNECT removes peer_name even if an error handler kept the context options
--EXTENSIONS--
openssl
--SKIPIF--
<?php require 'server.inc'; http_server_skipif(); ?>
--INI--
allow_url_fopen=1
--FILE--
<?php
require 'server.inc';

$responses = array(
    "data://text/plain,HTTP/1.0 200 Connection established\r\n\r\n",
    "data://text/plain,",
);

['pid' => $pid, 'uri' => $uri] = http_server($responses, $output);

$host = parse_url($uri, PHP_URL_HOST);
$port = parse_url($uri, PHP_URL_PORT);

$ctx = stream_context_create([
    'http' => [
        'proxy' => "tcp://$host:$port",
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

$held = [];
set_error_handler(function () use ($ctx, &$held) {
    $held[] = stream_context_get_options($ctx);
    return true;
});
fopen("https://www.php.net/test", 'r', false, $ctx);
restore_error_handler();

var_dump(isset(stream_context_get_options($ctx)['ssl']['peer_name']));

http_server_kill($pid);
?>
--EXPECT--
bool(false)
