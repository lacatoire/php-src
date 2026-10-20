--TEST--
session_destroy(), session_unset(): a destructor of an object in $_SESSION can call session functions
--INI--
session.use_cookies=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();

class D {
    public static $fn;
    public function __destruct() {
        $fn = self::$fn;
        if ($fn) {
            self::$fn = null;
            $fn();
        }
        echo "destructor done\n";
    }
}

foreach (['session_write_close', 'session_unset', 'session_destroy', 'session_reset', 'session_encode'] as $fn) {
    echo "$fn from session_destroy()\n";
    session_start();
    $_SESSION['a'] = new stdClass;
    $_SESSION['o'] = new D;
    unset($_SESSION);
    D::$fn = fn() => @$fn();
    var_dump(session_destroy());

    echo "$fn from session_unset()\n";
    session_start();
    $_SESSION['a'] = new stdClass;
    $_SESSION['o'] = new D;
    D::$fn = fn() => @$fn();
    var_dump(session_unset());
    D::$fn = null;
    @session_destroy();
}
echo "end\n";
?>
--EXPECT--
session_write_close from session_destroy()
destructor done
bool(true)
session_write_close from session_unset()
destructor done
bool(true)
session_unset from session_destroy()
destructor done
bool(true)
session_unset from session_unset()
destructor done
bool(true)
session_destroy from session_destroy()
destructor done
bool(true)
session_destroy from session_unset()
destructor done
bool(true)
session_reset from session_destroy()
destructor done
bool(true)
session_reset from session_unset()
destructor done
bool(true)
session_encode from session_destroy()
destructor done
bool(true)
session_encode from session_unset()
destructor done
bool(true)
end
