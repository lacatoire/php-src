--TEST--
mb_str_split() with invalid UTF-8 agrees with mb_strlen()
--EXTENSIONS--
mbstring
--FILE--
<?php
foreach (["\xe3ab", "\xe3\x81a", "\xe3\x81\xe3\x81", "\xe3\x81\x82\xe3ab", "\xf0\x9f\x98", "\xe3"] as $s) {
    echo bin2hex($s), " ", json_encode(array_map('bin2hex', mb_str_split($s, 1, 'UTF-8'))), " strlen=", mb_strlen($s, 'UTF-8'), "\n";
}
echo json_encode(array_map('bin2hex', mb_str_split("\xe3ab\xe3\x81\x82", 2, 'UTF-8'))), "\n";
?>
--EXPECT--
e36162 ["e3","61","62"] strlen=3
e38161 ["e381","61"] strlen=2
e381e381 ["e381","e381"] strlen=2
e38182e36162 ["e38182","e3","61","62"] strlen=4
f09f98 ["f09f98"] strlen=1
e3 ["e3"] strlen=1
["e361","62e38182"]
