--TEST--
stream_socket_enable_crypto(): an error handler cannot close the stream while crypto is set up
--EXTENSIONS--
openssl
--FILE--
<?php

$server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
$client = stream_socket_client(stream_socket_get_name($server, false));
stream_set_blocking($client, false);
stream_context_set_option($client, 'ssl', 'security_level', 9);

set_error_handler(function ($errno, $errstr) use (&$client) {
    echo $errstr, "\n";
    var_dump(@fclose($client));
    return true;
});

var_dump(stream_socket_enable_crypto($client, true, STREAM_CRYPTO_METHOD_TLS_CLIENT));
var_dump(is_resource($client));

?>
--EXPECT--
stream_socket_enable_crypto(): Security level must be between 0 and 5
bool(false)
int(0)
bool(true)
