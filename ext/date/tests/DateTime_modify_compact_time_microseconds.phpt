--TEST--
DateTime::modify(): a compact time of day ("1400") resets the microseconds like "14:00"
--FILE--
<?php
foreach (['1400', '14:00', '14:00:30', '1400 +1 day', 'noon', '+1 hour', 'tomorrow'] as $modifier) {
    $d = new DateTime('2024-01-01 10:30:45.123456');
    $d->modify($modifier);
    echo str_pad($modifier, 12), $d->format('Y-m-d H:i:s.u'), "\n";
}
$i = new DateTimeImmutable('2024-01-01 10:30:45.123456');
echo $i->modify('1400')->format('H:i:s.u'), "\n";
?>
--EXPECT--
1400        2024-01-01 14:00:00.000000
14:00       2024-01-01 14:00:00.000000
14:00:30    2024-01-01 14:00:30.000000
1400 +1 day 2024-01-02 14:00:00.000000
noon        2024-01-01 12:00:00.000000
+1 hour     2024-01-01 11:30:45.123456
tomorrow    2024-01-02 00:00:00.000000
14:00:00.000000
