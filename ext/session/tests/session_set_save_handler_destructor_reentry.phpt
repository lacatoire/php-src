--TEST--
session_set_save_handler(): a destructor of the previous handler can call the function again
--INI--
session.use_cookies=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();

class D {
    public function __destruct() {
        echo "destructor\n";
        @session_set_save_handler('o2', 'c2', 'r2', 'w2', 'd2', 'g2');
    }
}

function o($p, $n) { return true; } function c() { return true; } function r($i) { return ''; }
function w($i, $d) { return true; } function d($i) { return true; } function g($m) { return 0; }
function o2($p, $n) { return true; } function c2() { return true; } function r2($i) { return ''; }
function w2($i, $d) { return true; } function d2($i) { return true; } function g2($m) { return 0; }

$d = new D;
$cl = function ($p, $n) use ($d) { return true; };
@session_set_save_handler($cl, 'c', 'r', 'w', 'd', 'g');
unset($d, $cl);
@session_set_save_handler('o', 'c', 'r', 'w', 'd', 'g');
echo "after\n";

class H {
    public function __destruct() {
        echo "object destructor\n";
        session_set_save_handler(new SessionHandler);
    }
}
class H2 extends SessionHandler {
    public $h;
}
$h = new H2;
$h->h = new H;
session_set_save_handler($h);
unset($h);
session_set_save_handler(new SessionHandler);
echo "done\n";
?>
--EXPECT--
destructor
after
object destructor
done
