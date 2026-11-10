--TEST--
Bug #24063 (serialize() missing 0 after the . on scientific notation)
--FILE--
<?php
foreach ([1e-1, 1e-2, 1e-3, 1e-4, 1e-5, 1e-6, 1e-7, 1e-8, 1e-9] as $v) {
    echo serialize($v), ' ', unserialize(serialize($v)), "\n";
}
?>
--EXPECT--
d:0.1; 0.1
d:0.01; 0.01
d:0.001; 0.001
d:0.0001; 0.0001
d:1.0E-5; 1.0E-5
d:1.0E-6; 1.0E-6
d:1.0E-7; 1.0E-7
d:1.0E-8; 1.0E-8
d:1.0E-9; 1.0E-9
