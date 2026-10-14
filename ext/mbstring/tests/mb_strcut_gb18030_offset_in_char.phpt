--TEST--
mb_strcut() with GB18030 and an offset inside a character keeps the end of the string
--EXTENSIONS--
mbstring
--FILE--
<?php
$two = str_repeat("\xd7\xd6", 3);
foreach ([0, 1, 2, 3, 5] as $from) {
    echo $from, " ", bin2hex(mb_strcut($two, $from, null, "GB18030")), "\n";
}
$four = str_repeat("\x81\x30\x89\x38", 3);
foreach ([0, 1, 4, 5] as $from) {
    echo $from, " ", bin2hex(mb_strcut($four, $from, null, "GB18030")), "\n";
}
echo bin2hex(mb_strcut($two, 1, 2, "GB18030")), "\n";
?>
--EXPECT--
0 d7d6d7d6d7d6
1 d7d6d7d6d7d6
2 d7d6d7d6
3 d7d6d7d6
5 d7d6
0 813089388130893881308938
1 813089388130893881308938
4 8130893881308938
5 8130893881308938
d7d6
