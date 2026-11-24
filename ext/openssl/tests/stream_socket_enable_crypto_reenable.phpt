--TEST--
stream_socket_enable_crypto() can enable encryption again after disabling it
--EXTENSIONS--
openssl
--FILE--
<?php
$pem = __DIR__ . DIRECTORY_SEPARATOR . 'stream_socket_enable_crypto_reenable.pem.tmp';

include 'CertificateGenerator.inc';
(new CertificateGenerator())->saveNewCertAsFileWithKey('stream_socket_enable_crypto_reenable', $pem);

function handshake($client, $server): void
{
    do {
        $clientDone = stream_socket_enable_crypto($client, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        $serverDone = stream_socket_enable_crypto($server, true, STREAM_CRYPTO_METHOD_TLS_SERVER);
    } while ($clientDone === 0 || $serverDone === 0);
    var_dump($clientDone, $serverDone);
}

$listener = stream_socket_server('tcp://127.0.0.1:0', context: stream_context_create(['ssl' => ['local_cert' => $pem]]));
$client = stream_socket_client('tcp://' . stream_socket_get_name($listener, false), context: stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
$server = stream_socket_accept($listener);
stream_set_blocking($client, false);
stream_set_blocking($server, false);

handshake($client, $server);

/* Both sides leave TLS: each one sends its close_notify and reads what the peer sent. */
stream_socket_enable_crypto($client, false);
fread($server, 8192);
stream_socket_enable_crypto($server, false);
do {
    $read = [$client];
    $write = $except = null;
} while (stream_select($read, $write, $except, 0, 100000) && fread($client, 8192) !== '');

handshake($client, $server);
var_dump(fwrite($client, 'encrypted again'));
var_dump(fread($server, 8192));
?>
--CLEAN--
<?php
@unlink(__DIR__ . DIRECTORY_SEPARATOR . 'stream_socket_enable_crypto_reenable.pem.tmp');
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
int(15)
string(15) "encrypted again"
