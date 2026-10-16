--TEST--
DateTime::setTimezone(): an uninitialized DateTimeZone is rejected
--FILE--
<?php
$d = new DateTime('2024-01-01 10:00:00', new DateTimeZone('Asia/Tokyo'));
$bad = (new ReflectionClass(DateTimeZone::class))->newInstanceWithoutConstructor();
try {
    $d->setTimezone($bad);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
echo $d->format('Y-m-d H:i:s P'), "\n";

$i = new DateTimeImmutable('2024-01-01 10:00:00', new DateTimeZone('Asia/Tokyo'));
try {
    var_dump($i->setTimezone($bad));
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
try {
    date_timezone_set($d, $bad);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
DateObjectError: Object of type DateTimeZone has not been correctly initialized by calling parent::__construct() in its constructor
2024-01-01 10:00:00 +09:00
DateObjectError: Object of type DateTimeZone has not been correctly initialized by calling parent::__construct() in its constructor
DateObjectError: Object of type DateTimeZone has not been correctly initialized by calling parent::__construct() in its constructor
