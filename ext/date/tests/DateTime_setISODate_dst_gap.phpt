--TEST--
DateTime::setISODate(): landing in a DST gap keeps the wall-clock fields and the timestamp consistent
--FILE--
<?php
date_default_timezone_set('Europe/Paris');

$a = new DateTime('2026-01-10 02:30:00');
$a->setISODate(2026, 13, 7); // 2026-03-29, spring-forward day
echo $a->format('Y-m-d H:i:s P'), "\n";
echo gmdate('Y-m-d H:i:s', (int) $a->format('U')), "\n";

$i = new DateTimeImmutable('2026-01-10 02:30:00');
echo $i->setISODate(2026, 13, 7)->format('Y-m-d H:i:s P'), "\n";
?>
--EXPECT--
2026-03-29 03:30:00 +02:00
2026-03-29 01:30:00
2026-03-29 03:30:00 +02:00
