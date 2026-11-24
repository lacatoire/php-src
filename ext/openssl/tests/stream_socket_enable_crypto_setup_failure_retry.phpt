--TEST--
stream_socket_enable_crypto() does not leak the SSL context when retried after a setup failure
--EXTENSIONS--
openssl
--FILE--
<?php

$listener = stream_socket_server('tcp://127.0.0.1:0');
$client = stream_socket_client('tcp://' . stream_socket_get_name($listener, false));
$server = stream_socket_accept($listener);

stream_context_set_option($client, 'ssl', 'ciphers', 'NOSUCH');
for ($i = 0; $i < 3; $i++) {
    var_dump(@stream_socket_enable_crypto($client, true, STREAM_CRYPTO_METHOD_TLS_CLIENT));
}

stream_context_set_option($server, 'ssl', 'alpn_protocols', 'h2');
stream_context_set_option($server, 'ssl', 'local_cert', __DIR__ . '/no_such_certificate.pem');
for ($i = 0; $i < 3; $i++) {
    var_dump(@stream_socket_enable_crypto($server, true, STREAM_CRYPTO_METHOD_TLS_SERVER));
}

?>
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
