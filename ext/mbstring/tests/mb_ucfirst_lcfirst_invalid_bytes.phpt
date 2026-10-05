--TEST--
mb_ucfirst() and mb_lcfirst() treat invalid bytes the same whether or not the first character changes
--EXTENSIONS--
mbstring
--FILE--
<?php
foreach (["A\xff", "a\xff"] as $s) {
    echo bin2hex(mb_ucfirst($s)), " ", bin2hex(mb_lcfirst($s)), "\n";
}
echo mb_ucfirst("+AEE-B", "UTF-7"), " ", mb_lcfirst("+AEE-B", "UTF-7"), "\n";
?>
--EXPECT--
413f 613f
413f 613f
AB aB
