--TEST--
strnatcasecmp(), natcasesort() and sort() fold case in ASCII only, whatever LC_CTYPE
--SKIPIF--
<?php
if (setlocale(LC_CTYPE, 'tr_TR.ISO-8859-9', 'tr_TR.iso88599') === false) {
    die('skip tr_TR.ISO-8859-9 locale not available');
}
?>
--FILE--
<?php
setlocale(LC_CTYPE, 'tr_TR.ISO-8859-9', 'tr_TR.iso88599');

var_dump(strnatcasecmp('i', 'I'), strcasecmp('i', 'I'));

$names = ['I2', 'i1', 'I10'];
natcasesort($names);
echo implode(',', $names), "\n";

$names = ['I2', 'i1', 'I10'];
sort($names, SORT_NATURAL | SORT_FLAG_CASE);
echo implode(',', $names), "\n";

var_dump(strnatcasecmp("\xE9", "\xC9"));
?>
--EXPECT--
int(0)
int(0)
i1,I2,I10
i1,I2,I10
int(1)
