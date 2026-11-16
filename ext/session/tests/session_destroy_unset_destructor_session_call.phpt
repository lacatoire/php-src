--TEST--
session_destroy(), session_unset(): a destructor of a session value calling session_*()
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
class D {
    public static string $fn;
    function __destruct() { (self::$fn)(); echo "dtor done\n"; }
}

foreach (['session_write_close', 'session_unset', 'session_destroy', 'session_reset', 'session_abort'] as $fn) {
    D::$fn = $fn;
    echo "$fn via session_destroy()\n";
    session_start();
    $_SESSION['a'] = new stdClass;
    $_SESSION['o'] = new D;
    unset($_SESSION);
    session_destroy();

    echo "$fn via session_unset()\n";
    session_start();
    $_SESSION['a'] = new stdClass;
    $_SESSION['o'] = new D;
    session_unset();
    session_abort();
}
echo "end\n";
?>
--EXPECTF--
session_write_close via session_destroy()
dtor done
session_write_close via session_unset()
dtor done
session_unset via session_destroy()
dtor done
session_unset via session_unset()
dtor done
session_destroy via session_destroy()
dtor done
session_destroy via session_unset()
dtor done
session_reset via session_destroy()
dtor done
session_reset via session_unset()
dtor done
session_abort via session_destroy()
dtor done
session_abort via session_unset()
dtor done
end
