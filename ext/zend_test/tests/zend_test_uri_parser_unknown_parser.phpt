--TEST--
zend_test_uri_parser(): an unknown parser is reported as argument #2
--EXTENSIONS--
zend_test
--FILE--
<?php

try {
    zend_test_uri_parser("https://example.com", "nosuchparser");
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

?>
--EXPECT--
ValueError: zend_test_uri_parser(): Argument #2 ($parser) Unknown parser
