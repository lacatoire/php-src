--TEST--
metaphone() does not allocate a buffer sized from a huge max_phonemes
--FILE--
<?php
var_dump(metaphone("Thompson", 5));
var_dump(metaphone("Thompson", 200000000));
var_dump(metaphone("Thompson", PHP_INT_MAX));
var_dump(metaphone("", PHP_INT_MAX));
?>
--EXPECTF--
Deprecated: Function metaphone() is deprecated since %s, use a userland phonetic matching library instead in %s on line %d
string(5) "0MPSN"

Deprecated: Function metaphone() is deprecated since %s, use a userland phonetic matching library instead in %s on line %d
string(5) "0MPSN"

Deprecated: Function metaphone() is deprecated since %s, use a userland phonetic matching library instead in %s on line %d
string(5) "0MPSN"

Deprecated: Function metaphone() is deprecated since %s, use a userland phonetic matching library instead in %s on line %d
string(0) ""
