--TEST--
mb_encode_numericentity() with 0xFFFFFFFF in conversion map
--EXTENSIONS--
mbstring
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip this test is for 64bit platform only"); ?>
--FILE--
<?php

// Regression test; the old implementation could only emit hexadecimal entities with about 5 digits
$convmap = [0x41, 0x41, 0x540a0ab5, 0x5a485054];
echo "13: " . mb_encode_numericentity("A", $convmap, "ASCII", true) . "\n";

?>
--EXPECT--
13: &#x50080054;
