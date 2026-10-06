--TEST--
session_start(): a session id that contains a NUL byte is replaced
--INI--
session.use_cookies=0
session.use_strict_mode=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();
foreach (["a\0b", "x\0\"qq", "\0"] as $id) {
    session_id($id);
    var_dump(session_start());
    var_dump(session_id() !== substr($id, 0, strcspn($id, "\0")) && strlen(session_id()) > 1);
    session_destroy();
}
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
