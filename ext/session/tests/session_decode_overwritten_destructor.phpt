--TEST--
session_decode(): the destructor of a value being overwritten replaces $_SESSION
--EXTENSIONS--
session
--SKIPIF--
<?php include 'skipif.inc'; ?>
--INI--
session.use_cookies=0
session.cache_limiter=
session.save_handler=files
--FILE--
<?php
ob_start();
class D {
    function __destruct() { $_SESSION = []; }
}

foreach (['php' => 'n|i:1;', 'php_binary' => "\x01ni:1;", 'php_serialize' => serialize(['n' => 1])] as $handler => $data) {
    ini_set('session.serialize_handler', $handler);
    session_start();
    $_SESSION['n'] = new D();
    var_dump(session_decode($data));
    echo "$handler survived\n";
    session_abort();
}
?>
--EXPECT--
bool(true)
php survived
bool(true)
php_binary survived
bool(true)
php_serialize survived
