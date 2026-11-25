--TEST--
textdomain() rejects a domain containing null bytes
--EXTENSIONS--
gettext
--FILE--
<?php
foreach (["\0", "0\0x", "ab\0cd"] as $domain) {
    try {
        textdomain($domain);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
?>
--EXPECT--
ValueError: textdomain(): Argument #1 ($domain) must not contain any null bytes
ValueError: textdomain(): Argument #1 ($domain) must not contain any null bytes
ValueError: textdomain(): Argument #1 ($domain) must not contain any null bytes
