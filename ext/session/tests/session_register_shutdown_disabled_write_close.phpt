--TEST--
session_register_shutdown(): does not crash when session_write_close() is disabled
--INI--
disable_functions=session_write_close
session.use_cookies=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
var_dump(function_exists('session_write_close'));
session_register_shutdown();
echo "done\n";
?>
--EXPECT--
bool(false)
done
