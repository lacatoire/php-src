--TEST--
session_set_cookie_params(): keys differing only by case do not leak the first value
--INI--
session.use_cookies=1
session.cache_limiter=
--EXTENSIONS--
session
--FILE--
<?php
ob_start();
$p = str_repeat('x', 100) . mt_rand();
var_dump(session_set_cookie_params([
    'path' => "/a$p", 'PATH' => '/b',
    'lifetime' => "1$p", 'LIFETIME' => '20',
    'domain' => "d$p", 'Domain' => 'e',
    'samesite' => "Lax$p", 'SameSite' => 'Strict',
]));
var_dump(session_get_cookie_params());
?>
--EXPECT--
bool(true)
array(7) {
  ["lifetime"]=>
  int(20)
  ["path"]=>
  string(2) "/b"
  ["domain"]=>
  string(1) "e"
  ["secure"]=>
  bool(false)
  ["partitioned"]=>
  bool(false)
  ["httponly"]=>
  bool(true)
  ["samesite"]=>
  string(6) "Strict"
}
