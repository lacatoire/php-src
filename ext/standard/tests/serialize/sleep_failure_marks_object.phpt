--TEST--
serialize(): a failed __sleep() marks the object, and the incomplete class count is exact
--FILE--
<?php
class S { public $a = 1; function __sleep() { return null; } }
$o = new S;
$p = new stdClass;

foreach ([[$o, $o], [$o, [$o]], [$o, $p, $p]] as $value) {
    $s = @serialize($value);
    echo $s, "\n";
    var_dump(@unserialize($s) !== false);
}

echo serialize(unserialize('O:22:"__PHP_Incomplete_Class":1:{s:1:"a";i:1;}')), "\n";
echo serialize(unserialize('O:22:"__PHP_Incomplete_Class":2:{s:1:"a";i:1;s:1:"b";i:2;}')), "\n";
echo serialize(unserialize('O:7:"Unknown":1:{s:1:"a";i:1;}')), "\n";
?>
--EXPECT--
a:2:{i:0;N;i:1;N;}
bool(true)
a:2:{i:0;N;i:1;a:1:{i:0;N;}}
bool(true)
a:3:{i:0;N;i:1;O:8:"stdClass":0:{}i:2;r:3;}
bool(true)
O:22:"__PHP_Incomplete_Class":1:{s:1:"a";i:1;}
O:22:"__PHP_Incomplete_Class":2:{s:1:"a";i:1;s:1:"b";i:2;}
O:7:"Unknown":1:{s:1:"a";i:1;}
