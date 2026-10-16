--TEST--
jdtojewish(): the year range in the Hebrew mode error starts at 1
--EXTENSIONS--
calendar
--FILE--
<?php
try {
    jdtojewish(1, true);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--EXPECT--
ValueError: Year out of range (1-9999)
