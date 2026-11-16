--TEST--
DateTime::setDate(): landing in a DST gap keeps the wall-clock fields and the timestamp consistent
--FILE--
<?php
date_default_timezone_set('Europe/Paris');

$d = new DateTime('2023-01-15 02:30:00');
$d->setDate(2023, 3, 26);
echo $d->format('Y-m-d H:i:s P'), "\n";
echo gmdate('Y-m-d H:i:s', (int) $d->format('U')), "\n";

$i = new DateTimeImmutable('2023-01-15 02:30:00');
$i = $i->setDate(2023, 3, 26);
echo $i->format('Y-m-d H:i:s P'), "\n";
?>
--EXPECT--
2023-03-26 03:30:00 +02:00
2023-03-26 01:30:00
2023-03-26 03:30:00 +02:00
