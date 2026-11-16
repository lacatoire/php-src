--TEST--
DateTime::format(): 'y' keeps two digits for a negative year
--FILE--
<?php
$d = new DateTime('0075-06-15');
$d->modify('-150 years');
echo $d->format('Y'), ' ', $d->format('y'), "\n";
$d->modify('-25 years');
echo $d->format('Y'), ' ', $d->format('y'), "\n";
$d->setDate(-100, 1, 1);
echo $d->format('Y'), ' ', $d->format('y'), "\n";
$d->setDate(-5, 1, 1);
echo $d->format('Y'), ' ', $d->format('y'), "\n";
$d->setDate(1999, 1, 1);
echo $d->format('Y'), ' ', $d->format('y'), "\n";
?>
--EXPECT--
-0075 -75
-0100 -00
-0100 -00
-0005 -05
1999 99
