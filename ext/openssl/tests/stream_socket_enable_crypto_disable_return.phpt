--TEST--
stream_socket_enable_crypto() return value when disabling encryption
--EXTENSIONS--
openssl
--SKIPIF--
<?php
if (!function_exists("proc_open")) die("skip no proc_open");
?>
--FILE--
<?php
$certFile = __DIR__ . DIRECTORY_SEPARATOR . 'stream_socket_enable_crypto_disable_return.pem.tmp';

$serverCode = <<<'CODE'
    $serverCtx = stream_context_create(['ssl' => ['local_cert' => '%s']]);
    $sock = stream_socket_server("tls://127.0.0.1:0", $errno, $errstr,
        STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $serverCtx);
    phpt_notify_server_start($sock);

    $link = stream_socket_accept($sock);
    fread($link, 1);
    phpt_wait();
    fclose($link);
CODE;
$serverCode = sprintf($serverCode, $certFile);

$clientCode = <<<'CODE'
    $clientCtx = stream_context_create(['ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]]);
    $sock = stream_socket_client("tls://{{ ADDR }}", $errno, $errstr, 2, STREAM_CLIENT_CONNECT, $clientCtx);

    echo "Encrypted stream:\n";
    var_dump(stream_socket_enable_crypto($sock, false));
    echo "Encryption already disabled:\n";
    var_dump(stream_socket_enable_crypto($sock, false));
    phpt_notify();

    echo "Stream without crypto support:\n";
    [$a, $b] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
    var_dump(stream_socket_enable_crypto($a, false));
CODE;

include 'CertificateGenerator.inc';
(new CertificateGenerator())->saveNewCertAsFileWithKey('stream_socket_enable_crypto_disable_return', $certFile);

include 'ServerClientTestCase.inc';
ServerClientTestCase::getInstance()->run($clientCode, $serverCode);
?>
--CLEAN--
<?php
@unlink(__DIR__ . DIRECTORY_SEPARATOR . 'stream_socket_enable_crypto_disable_return.pem.tmp');
?>
--EXPECTF--
Encrypted stream:
bool(true)
Encryption already disabled:
bool(false)
Stream without crypto support:

Warning: stream_socket_enable_crypto(): This stream does not support SSL/crypto in %s on line %d
bool(false)
