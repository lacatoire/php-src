--TEST--
session_start(): an option whose value is a reference is accepted
--INI--
session.use_cookies=0
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();
$options = ['cookie_lifetime' => 77, 'name' => 'REFX'];
foreach ($options as &$value) {}
unset($value);
var_dump(session_start($options));
var_dump(session_name());
var_dump(ini_get('session.cookie_lifetime'));
session_destroy();
?>
--EXPECT--
bool(true)
string(4) "REFX"
string(2) "77"
