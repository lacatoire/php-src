--TEST--
session_encode() with php_serialize: __sleep() replacing $_SESSION
--EXTENSIONS--
session
--SKIPIF--
<?php include 'skipif.inc'; ?>
--INI--
session.use_cookies=0
session.cache_limiter=
session.serialize_handler=php_serialize
session.save_handler=files
--FILE--
<?php
class A {
    public $p = 1;
    function __sleep() { $_SESSION = []; return ['p']; }
}
class B {
    public $p = 1;
    function __serialize(): array { session_unset(); return ['p' => 1]; }
}

session_start();
$_SESSION['a'] = new A;
$_SESSION['b'] = new A;
$_SESSION['c'] = str_repeat('x', 100);
var_dump(session_encode());

session_abort();
session_start();
$_SESSION['a'] = new B;
$_SESSION['b'] = new B;
$_SESSION['c'] = str_repeat('x', 100);
var_dump(session_encode());
?>
--EXPECT--
string(187) "a:3:{s:1:"a";O:1:"A":1:{s:1:"p";i:1;}s:1:"b";O:1:"A":1:{s:1:"p";i:1;}s:1:"c";s:100:"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";}"
string(187) "a:3:{s:1:"a";O:1:"B":1:{s:1:"p";i:1;}s:1:"b";O:1:"B":1:{s:1:"p";i:1;}s:1:"c";s:100:"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";}"