--TEST--
session_start(), session_reset(): a destructor of the previous $_SESSION can call session_unset() and session_reset()
--INI--
session.use_cookies=0
session.use_strict_mode=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();

$call = null;
class D {
    public function __destruct() {
        global $call;
        if ($call) {
            $fn = $call;
            $call = null;
            $fn();
        }
    }
}

session_id('fixedid1');
foreach (['session_unset', 'session_reset'] as $fn) {
    session_start();
    $_SESSION['a'] = 1;
    $_SESSION['d'] = new D;
    session_write_close();

    // session_start() releases the previous $_SESSION
    $call = $fn;
    var_dump(session_start());
    session_write_close();

    // session_reset() releases the $_SESSION that was just read
    session_start();
    $_SESSION['d'] = new D;
    $call = $fn;
    var_dump(session_reset());
    session_destroy();
}
echo "end\n";
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
end
