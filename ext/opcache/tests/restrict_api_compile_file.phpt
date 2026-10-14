--TEST--
opcache.restrict_api is enforced by opcache_compile_file() and opcache_jit_blacklist()
--EXTENSIONS--
opcache
--INI--
opcache.enable_cli=1
opcache.restrict_api=/nonexistent
--FILE--
<?php
var_dump(opcache_compile_file(__FILE__));
var_dump(opcache_is_script_cached(__FILE__));
var_dump(opcache_jit_blacklist(function () {}));
?>
--EXPECTF--

Warning: Zend OPcache API is restricted by "restrict_api" configuration directive in %s on line %d
bool(false)

Warning: Zend OPcache API is restricted by "restrict_api" configuration directive in %s on line %d
bool(false)

Warning: Zend OPcache API is restricted by "restrict_api" configuration directive in %s on line %d
NULL
