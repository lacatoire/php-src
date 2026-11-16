--TEST--
DateInterval::format(): y, m, d, h and i are not truncated to 32 bits
--FILE--
<?php
$i = new DateInterval('P1D');
foreach (['y', 'm', 'd', 'h', 'i'] as $p) {
    $i->$p = PHP_INT_MAX;
    echo $p, ': ', $i->format('%' . $p), ' ', $i->format('%' . strtoupper($p)), "\n";
    $i->$p = 2**32 + 5;
    echo $p, ': ', $i->format('%' . $p), ' ', $i->format('%' . strtoupper($p)), "\n";
    $i->$p = 7;
    echo $p, ': ', $i->format('%' . $p), ' ', $i->format('%' . strtoupper($p)), "\n";
}
?>
--EXPECT--
y: 9223372036854775807 9223372036854775807
y: 4294967301 4294967301
y: 7 07
m: 9223372036854775807 9223372036854775807
m: 4294967301 4294967301
m: 7 07
d: 9223372036854775807 9223372036854775807
d: 4294967301 4294967301
d: 7 07
h: 9223372036854775807 9223372036854775807
h: 4294967301 4294967301
h: 7 07
i: 9223372036854775807 9223372036854775807
i: 4294967301 4294967301
i: 7 07
