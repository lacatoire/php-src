--TEST--
parse_ini_string() INI_SCANNER_TYPED converts negative decimal numbers to float
--FILE--
<?php
foreach (["1.5", "-1.5", ".5", "-.5", "2", "-2", "1.", "-1."] as $v) {
    $r = parse_ini_string("k = $v\n", false, INI_SCANNER_TYPED);
    var_dump($r["k"]);
}
?>
--EXPECT--
float(1.5)
float(-1.5)
float(0.5)
float(-0.5)
int(2)
int(-2)
float(1)
float(-1)
